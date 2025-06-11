import smtplib
import os
from email.message import EmailMessage

EMAIL_ADDRESS = os.environ['EMAIL_USER']
EMAIL_PASSWORD = os.environ['EMAIL_PASS']

msg = EmailMessage()
msg['Subject'] = '🚀 Se hizo push al repositorio'
msg['From'] = EMAIL_ADDRESS
msg['To'] = EMAIL_ADDRESS  # Puedes cambiarlo a otro correo si deseas

msg.set_content('Se realizó un push al repositorio y se ejecutó la acción Stats.')

with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
    smtp.login(EMAIL_ADDRESS, EMAIL_PASSWORD)
    smtp.send_message(msg)
