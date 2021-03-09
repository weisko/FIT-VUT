#!/usr/bin/env python3

#GET /data/2.5/weather?q=CITY&APPID=KEY&units=metric HTTP/1.1\r\nHost: api.openweathermap.org\r\n\r\n
#api.openweathermap.org/data/2.5/weather?q=London,uk&APPID=99dec1d1e5eabd823f18564879a45448

import socket
import json
import sys


HOST = '146.185.181.89'
PORT = 80

key = sys.argv[1]
CITY = sys.argv[2]
CITY =  CITY.lower()

request = 'GET /data/2.5/weather?q=' + CITY + '&APPID=' + key + '&units=metric HTTP/1.1\r\nHost: api.openweathermap.org\r\n\r\n'

try:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST, PORT))
    s.send(request.encode())
    results = s.recv(4096)
except Exception as error:
   exit("Request was not accepted!\n")

results = str(results)
index_of_json = (results.find('{'))
results_new = results[index_of_json:-1]

if '400' in results:
    exit("Error:400 - Bad request(check city name)")
if  "401" in results:
    exit("Error:401 - Unauthorized access(check api key?)")
if '404' in results:
    exit("Error:404 - Not Found(server didnt find what was requested)")
if '408' in results:
    exit("Error:408 - Request Timeout")
try:
    data = json.loads(results_new)
    description = str(data['weather'][0]['description'])
    temperature = str(data['main']['temp'])
    humidity = str(data['main']['humidity'])
    pressure = str(data['main']['pressure'])
    wind_speed = str(data['wind']['speed'])

    CITY = CITY.title()
    print(CITY)
    print(description)
    print('temp: ' + temperature + '°C')
    print('humidity: ' + humidity + '%')
    print('pressure: ' + pressure + ' hPa')
    print('wind-speed: ' + wind_speed + ' km/h')

    if 'deg' in (data['wind']):
        wind_deg = str(data['wind']['deg'])
        print('wind-deg: ' + wind_deg)
    else:
        print('wind-deg: Not available')
except Exception as error:
    exit("JSON data did not loaded properly!\n")
