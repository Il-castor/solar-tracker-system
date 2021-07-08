# -*- coding: utf-8 -*-
import serial
import string
import datetime
import mysql.connector
from threading import*

ard = serial.Serial('/dev/ttyACM0',9600) #imposto variabile per la lettura da seriale
ard.flushInput()


def passa_paranetri(busVoltage, current_mA, potenza, temperatura, date, oraa):
	try:
		mydb = mysql.connector.connect(
		host="localhost",
		user="Castor",
		passwd="CellPhone299",
		database="db_tesina"
		)
		mycursor = mydb.cursor()
		
		sql = "INSERT INTO db_tesina.misurazioni (tensione, corrente, potenza, temperatura, data, ora) VALUES (%s, %s, %s, %s, %s)"
		val = (busVoltage, current_mA, potenza, temperatura, date, oraa)
		#val = ("0.10", "0.23", "2.3", "2019-01-13", "11:05")
		mycursor.execute(sql, val)
		mydb.commit()
	except mysql.connector.Error as error:
		mydb.rollback()
		print("non riesco a inserire la query nel db ".format(error))
	finally:
		if (mydb.is_connected()):
			mycursor.close()
			mydb.close
			print("db chiuso")


#def passa_paranetri(busVoltage, current_mA, potenza, date, oraa):
#	try:
#		mydb = mysql.connector.connect(
#		host="castor1.altervista.org",
#		user="castor1",
#		passwd="9Wr4dHZY4kyk",
#		database="my_castor1"
#		)
#		mycursor = mydb.cursor()
		
#		sql = "INSERT INTO my_castor1.misurazioni (tensione, corrente, potenza, data, ora) VALUES (%s, %s, %s, %s, %s)"
#		val = (busVoltage, current_mA, potenza, date, oraa)
		#val = ("0.10", "0.23", "2.3", "2019-01-13", "11:05")
#		mycursor.execute(sql, val)
#		mydb.commit()
#	except mysql.connector.Error as error:
#		mydb.rollback()
#		print("non riesco a inserire la query nel db ".format(error))
#	finally:
#		if (mydb.is_connected()):
#			mycursor.close()
#			mydb.close
#			print("db chiuso")
#def connessione_db(busVoltage, current_mA, potenza, date, oraa):
	
#	conn = mysql.connector.connect(host='localhost', database='db_tesina', user='Castor',password='CellPhone299')
#	if conn.is_connected():
#		print('Connected to MySQL database')
#		passa_paranetri(busVoltage, current_mA, potenza, date, oraa)
#		connection.close()

def converti_stringa(dati_passati):
	data2 = dati_passati.split("#") #divido la stringa  la mette in un array
	#print(data2[0])
	#print (str(data2)) #stampo i pezzi di stringa e li separa con la virgola
	shuntVoltage= float(data2[0]) #caduta di tensione ai capi della resistenza shunt mV
	#print shuntVoltage
	busVoltage= float(data2[1]) #tensione totale vista dal circuito (Tensione di alimentazione- Tensione shunt). Valore in Volt
	#print busVoltage
	current_mA=float(data2[2]) #corrente ricavata tramite legge di Ohm dalla tensione shunt. Valore in mA
	#print current_mA
	loadVoltage=float(data2[3]) #tensione sul carico
	#print loadVoltage
	potenza = (current_mA*busVoltage)
	temperatura=int(data2[4]) #temperatura
	date = data() #date contiene una stringa
	
	print(date)
	oraa = ora() #ora contiene una stringa
	passa_paranetri(busVoltage, current_mA, potenza, temperatura, date, oraa) #passare parametri

def data():
	delimitatore ="-"
	x = datetime.datetime.now() #mi restituisce la data e ora corrente
	anno = x.strftime("%Y") #mi restituisce l'anno YYYY
	mese = x.strftime("%m") #mi restituisce il mese in numero
	giorno = x.strftime("%d") #mi restituisce il giorno in numero
	
	tempo = anno+delimitatore+mese+delimitatore+giorno #creo la stringa data per db
	return tempo
	#tempo sarebbe la data di misurazione 

def ora():
	x = datetime.datetime.now() #mi restituisce la data e ora corrente
	ora = x.strftime("%X") #mi restituisce l'ora in h/s/microsecondi
	return ora

	 


def hello():
	print("Hello word")
	
while (True):
	dati_passati = ard.readline()
	print(dati_passati)
	#t = Timer(5.0, converti_stringa, [lettura])
	#t.start()
	#t.join()
	converti_stringa(dati_passati)
	
	#ciclo for con perequazione ogni 5 minuti

#FINIRE DI PASSARE VALORI AL DB E TROVARE UN MODO PER PASSARLI IN MODO EFFICACE

