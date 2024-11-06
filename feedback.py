import json
import mysql.connector
import google.generativeai as genai
import sys

# Ensure the output encoding is set to UTF-8
sys.stdout.reconfigure(encoding='utf-8')

# Define your API key
API_KEY = 'AIzaSyAfBfElwABNMlbSmywgWA9qET6zIdh4EhI'

# Configure the API
genai.configure(api_key=API_KEY)
model = genai.GenerativeModel('gemini-1.5-flash')

# Read the lesson ID, student ID, and answers from the JSON file
try:
    with open('feedback_data.json', 'r') as file:
        data = json.load(file)
        lesson_id = data.get('lesson_id')
        student_id = data.get('student_id')
        answers = data.get('answers')
except (FileNotFoundError, json.JSONDecodeError) as e:
    print(f"Error reading JSON file: {e}")
    exit(1)

if not lesson_id or not student_id or not answers:
    print("Invalid data in JSON file.")
    exit(1)

# Establish a connection to the database
try:
    cnx = mysql.connector.connect(
        user='root',
        password='',
        host='127.0.0.1',
        database='projet'
    )
    cursor = cnx.cursor(dictionary=True)
except mysql.connector.Error as err:
    print(f"Error: {err}")
    exit(1)

# Fetch all answers for the given lesson ID and student ID
try:
    cursor.execute("""
        SELECT q.Question, q.Reponse AS CorrectAnswer, re.Reponse AS StudentAnswer
        FROM reponse_etudiant re
        INNER JOIN question q ON re.Id_Question = q.Id_Question
        WHERE re.Id_Etudiant = %s AND q.Id_Question IN (
            SELECT Id_Question FROM question WHERE Id_Exercice IN (
                SELECT Id_Exercice FROM exercice WHERE Id_lesson = %s
            )
        )
    """, (student_id, lesson_id))
    results = cursor.fetchall()
except mysql.connector.Error as err:
    print(f"Error fetching data: {err}")
    cnx.close()
    exit(1)

if not results:
    print("No data found.")
    cnx.close()
    exit(1)

questions = [result['Question'] for result in results]
correct_answers = [result['CorrectAnswer'] for result in results]
student_answers = [result['StudentAnswer'] for result in results]

# Construct the prompt for the AI
prompt = (f"Write a quick but detailed feedback in French for a student that has these questions: {questions} with respectively these answers: {correct_answers} "
          f"and he answered respectively with these answers: {student_answers}, write a feedback for this student do not just say it's incorrect give a little explanation, be strict don't hesitate to correct any mistake that isn't relevant to the correct answer, if the student's answers match the right answers I gave you then say 'Bravo!' and that's it, if the student didn't match the correct answer then you say it's false, write directly the feedback do not write anything but the feedback. Don't use bold text or '*', add in the end a funny motivational line in french always")
# Generate the feedback using the AI model
try:
    response = model.generate_content(prompt)
    feedback = response.text.strip()
    print(feedback)
    
except Exception as err:
    print(f"Error generating feedback: {err}")

# Close the database connection
cnx.close()
