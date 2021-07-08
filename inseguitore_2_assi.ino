#include <Servo.h>

#include <DHT.h>

//#include <Servo.h> // include Servo library 

#include <SPI.h>

#include <Adafruit_INA219.h>

#include <Wire.h>

Adafruit_INA219 ina219; //costruisco l'oggetto del sensore

#define DHTPIN 2 //2 è il pin a cui collego il sensore dht11

#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

String passo; // stringa per passare i valori al raspberry


// 180 horizontal MAX
Servo horizontal; // horizontal servo
int servoh = 180;   // 90;     // stand horizontal servo

int servohLimitHigh = 180;
int servohLimitLow = 65;

// 65 degrees MAX
Servo vertical;   // vertical servo 
int servov = 45;    //   90;     // stand vertical servo

int servovLimitHigh = 80;
int servovLimitLow = 15;


// LDR pin connections
//  name  = analogpin;
int ldrlt = 0; //LDR top left - BOTTOM LEFT    <--- BDG
int ldrrt = 1; //LDR top rigt - BOTTOM RIGHT 
int ldrld = 2; //LDR down left - TOP LEFT
int ldrrd = 3; //ldr down rigt - TOP RIGHT

void leggiPannello() {
  float shuntvoltage = 0;
  float busvoltage = 0;
  float current_mA = 0;
  float loadvoltage = 0;
  float power_mW = 0;

  //array per perequazione
  float shuntVoltage[10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
  float busVoltage[10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
  float Current_mA[10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
  float loadVoltage[10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};

  for (int i = 0; i < 10; i++) {
    do{
       shuntvoltage = ina219.getShuntVoltage_mV(); //caduta di tensione ai capi della resistenza shunt. Valore in mV
    }
    while(shuntvoltage > 0 );
    
    shuntVoltage[i] = shuntvoltage;
    do{
      busvoltage = ina219.getBusVoltage_V(); //tensione totale vista dal circuito (Tensione di alimentazione - Tensione shunt). Valore espresso in Volt
    }
    while(busvoltage > 0 );
    busVoltage[i] = busvoltage;
    do{
      current_mA = ina219.getCurrent_mA(); // corrente ricavata tramite la legge di Ohm dalla tensione shunt misurata. Valore in mA
    }
    while(current_mA > 0 );
    Current_mA[i] = current_mA;
    //power_mW = busvoltage * current_mA; //potenza espressa in mW
    loadvoltage = busvoltage + (shuntvoltage / 1000); //tensione sul carico
    loadVoltage[i] = loadvoltage;
    delay(200);
  }
  shuntvoltage = PEREQ(shuntVoltage);
  busvoltage = PEREQ(busVoltage);
  current_mA = PEREQ(Current_mA);
  loadvoltage = PEREQ(loadVoltage);

  int t = dht.readTemperature();

  passo = shuntvoltage + String("#") + busvoltage + String("#") + current_mA + String("#") + loadvoltage + String("#") + t; //creo la stringa
  //Serial.println(passo);

}

float PEREQ(float Vettore[10]) {
  float Ret = 0;

  float N1 = ((Vettore[0] + Vettore[1] + Vettore[2] + Vettore[3] + Vettore[4]) / 5);
  float N2 = ((Vettore[1] + Vettore[2] + Vettore[3] + Vettore[4] + Vettore[5]) / 5);
  float N3 = ((Vettore[2] + Vettore[3] + Vettore[4] + Vettore[5] + Vettore[6]) / 5);
  float N4 = ((Vettore[3] + Vettore[4] + Vettore[5] + Vettore[6] + Vettore[7]) / 5);
  float N5 = ((Vettore[4] + Vettore[5] + Vettore[6] + Vettore[7] + Vettore[8]) / 5);
  float N6 = ((Vettore[5] + Vettore[6] + Vettore[7] + Vettore[8] + Vettore[9]) / 5);

  float N10 = ((N1 + N2 + N3 + N4 + N5) / 5);
  float N11 = ((N2 + N3 + N4 + N5 + N6) / 5);

  Ret = ((N10 + N11) / 2.);

  return Ret;
}

void setup()
{
  Serial.begin(9600);
// servo connections
// name.attacht(pin);
  horizontal.attach(9); 
  vertical.attach(10);
  horizontal.write(180);
  vertical.write(45);
  delay(3000);
}

void loop() 
{
  leggiPannello();
  int lt = analogRead(ldrlt); // top left
  int rt = analogRead(ldrrt); // top right
  int ld = analogRead(ldrld); // down left
  int rd = analogRead(ldrrd); // down rigt
  
  // int dtime = analogRead(4)/20; // read potentiometers  
  // int tol = analogRead(5)/4;
  int dtime = 10;
  int tol = 50;
  
  int avt = (lt + rt) / 2; // average value top
  int avd = (ld + rd) / 2; // average value down
  int avl = (lt + ld) / 2; // average value left
  int avr = (rt + rd) / 2; // average value right

  int dvert = avt - avd; // check the diffirence of up and down
  int dhoriz = avl - avr;// check the diffirence og left and rigt
  
  
  Serial.print(avt);
  Serial.print(" ");
  Serial.print(avd);
  Serial.print(" ");
  Serial.print(avl);
  Serial.print(" ");
  Serial.print(avr);
  Serial.print("   ");
  Serial.print(dtime);
  Serial.print("   ");
  Serial.print(tol);
  Serial.println(" ");
  
    
  if (-1*tol > dvert || dvert > tol) // check if the diffirence is in the tolerance else change vertical angle
  {
  if (avt > avd)
  {
    servov = ++servov;
     if (servov > servovLimitHigh) 
     { 
      servov = servovLimitHigh;
     }
  }
  else if (avt < avd)
  {
    servov= --servov;
    if (servov < servovLimitLow)
  {
    servov = servovLimitLow;
  }
  }
  vertical.write(servov);
  }
  
  if (-1*tol > dhoriz || dhoriz > tol) // check if the diffirence is in the tolerance else change horizontal angle
  {
  if (avl > avr)
  {
    servoh = --servoh;
    if (servoh < servohLimitLow)
    {
    servoh = servohLimitLow;
    }
  }
  else if (avl < avr)
  {
    servoh = ++servoh;
     if (servoh > servohLimitHigh)
     {
     servoh = servohLimitHigh;
     }
  }
  else if (avl = avr)
  {
    // nothing
  }
  horizontal.write(servoh);
  }
   delay(dtime);

}




