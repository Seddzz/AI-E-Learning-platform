import mysql.connector
import google.generativeai as genai
import re

# Define your API key
API_KEY = 'AIzaSyAfBfElwABNMlbSmywgWA9qET6zIdh4EhI'

# Configure the API
genai.configure(api_key=API_KEY)
model = genai.GenerativeModel('gemini-1.5-flash')

# Establish a connection to the database
try:
    cnx = mysql.connector.connect(
        user='root',
        password='',
        host='127.0.0.1',
        database='projet'
    )
    cursor = cnx.cursor()
except mysql.connector.Error as err:
    print(f"Error: {err}")
    exit(1)

# Fetch the last inserted course
try:
    cursor.execute("""
        SELECT Id_Cours
        FROM cours
        ORDER BY Id_Cours DESC
        LIMIT 1
    """)
    last_course = cursor.fetchone()
    cursor.execute("""
        SELECT Titre_cours
        FROM cours
        ORDER BY Id_Cours DESC
        LIMIT 1
    """)
    Titre_cours = cursor.fetchone()
    print(f"Last inserted course: {last_course}")
except mysql.connector.Error as err:
    print(f"Error fetching last inserted course: {err}")
    cnx.close()
    exit(1)

if last_course:
    last_course_id = last_course[0]
    print(f"Last Course ID: {last_course_id}")

    # Fetch all lessons in the last inserted course
    try:
        cursor.execute("""
            SELECT Id_lesson, Titre_lesson
            FROM lesson
            WHERE Id_Cours = %s
            ORDER BY Id_lesson
        """, (last_course_id,))
        lessons = cursor.fetchall()
        print(f"Lessons fetched: {lessons}")
    except mysql.connector.Error as err:
        print(f"Error fetching lessons: {err}")
        cnx.close()
        exit(1)

    if not lessons:
        print(f"No lessons found for course ID: {last_course_id}. Please check the database.")
    else:
        for lesson in lessons:
            lesson_id = lesson[0]
            lesson_title = lesson[1]

            print(f"Fetched Lesson: {lesson_title} (ID: {lesson_id})")

            # Check if the exercise already exists for this lesson
            try:
                cursor.execute("SELECT Id_Exercice FROM exercice WHERE Nom_Exercice = %s AND Id_lesson = %s", (lesson_title, lesson_id))
                existing_exercise = cursor.fetchone()
                print(f"Existing exercise: {existing_exercise}")
            except mysql.connector.Error as err:
                print(f"Error checking existing exercise: {err}")
                continue

            if existing_exercise:
                print("Exercise already exists for this lesson.")
            else:
                # Create an exercise with the name of that lesson
                try:
                    cursor.execute("INSERT INTO exercice (Nom_Exercice, Id_lesson) VALUES (%s, %s)", (lesson_title, lesson_id))
                    cnx.commit()
                    exercice_id = cursor.lastrowid
                    print(f"Created exercise ID: {exercice_id}")
                except mysql.connector.Error as err:
                    print(f"Error creating exercise: {err}")
                    continue

                # Use the API to generate a quiz exercise with a specific format
                prompt = f"Create a quiz exercise in French, for the lesson titled '{lesson_title}' in the course '{Titre_cours}'. It should contain 3 questions, each with 3 options. Stick to this format. Format: '**Question n:** Question text | Option 1: Option text | Option 2: Option text | Option 3: Option text | Correct Answer: Option text without writing the option number, write directly'. Don't add ** for \"Correct Answer\""
                try:
                    response = model.generate_content(prompt)
                    exercise_text = response.text.strip()
                    print("Generated Exercise Text:")
                    print(exercise_text)
                except Exception as err:
                    print(f"Error generating exercise content: {err}")
                    continue

                # Extract questions and options from the response using regex
                question_pattern = re.compile(r'\*\*Question \d+:\*\* (.*?)\s*\|\s*Option 1:\s*(.*?)\s*\|\s*Option 2:\s*(.*?)\s*\|\s*Option 3:\s*(.*?)\s*\|\s*Correct Answer:\s*(.*?)$', re.MULTILINE | re.DOTALL)
                questions = question_pattern.findall(exercise_text)
                print(f"Extracted questions: {questions}")

                for question_data in questions:  # Ensure we only process 3 questions
                    question_text, opt1, opt2, opt3, answer = [text.strip() for text in question_data]
                    # Remove "Option n:" text from options
                    opt1 = opt1.strip()
                    opt2 = opt2.strip()
                    opt3 = opt3.strip()
                    answer = answer.strip()

                    print(f"Processing Question: {question_text}")
                    print(f"Options: {opt1}, {opt2}, {opt3}")
                    print(f"Correct Answer: {answer}")

                    # Insert the question
                    try:
                        cursor.execute("INSERT INTO question (Question, Reponse, Id_Exercice) VALUES (%s, %s, %s)", (question_text, answer, exercice_id))
                        cnx.commit()
                        question_id = cursor.lastrowid
                        print(f"Inserted Question ID: {question_id}")
                    except mysql.connector.Error as err:
                        print(f"Error inserting question: {err}")
                        continue

                    # Insert the options
                    try:
                        cursor.execute("INSERT INTO `option` (Text, Is_Correct, Id_Question) VALUES (%s, %s, %s)", (opt1, int(opt1 == answer), question_id))
                        cursor.execute("INSERT INTO `option` (Text, Is_Correct, Id_Question) VALUES (%s, %s, %s)", (opt2, int(opt2 == answer), question_id))
                        cursor.execute("INSERT INTO `option` (Text, Is_Correct, Id_Question) VALUES (%s, %s, %s)", (opt3, int(opt3 == answer), question_id))
                        cnx.commit()
                    except mysql.connector.Error as err:
                        print(f"Error inserting options: {err}")
                        continue

                print("Exercise and questions inserted successfully.")

# Close the database connection
cnx.close()
