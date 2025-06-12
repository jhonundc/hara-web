import smtplib
import os
from email.message import EmailMessage

EMAIL_ADDRESS = os.environ['EMAIL_USER']
EMAIL_PASSWORD = os.environ['EMAIL_PASS']

msg = EmailMessage()
msg['Subject'] = ' Push detectado en el repositorio'
msg['From'] = EMAIL_ADDRESS
msg['To'] = EMAIL_ADDRESS  

msg.set_content('Se realizó un push a la rama main y se ejecutó la acción Stats en GitHub Actions.')

with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
    smtp.login(EMAIL_ADDRESS, EMAIL_PASSWORD)
    smtp.send_message(msg)
