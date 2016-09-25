import RPi.GPIO as GPIO
import MySQLdb
from login import *

class io:
    def __init__(self, row):
        self.number = row[0] + 1
        self.name = row[1]
        self.isOutput = str(row[2])
        self.isHigh = str(row[3])
        
GPIO.setmode(GPIO.BCM)

def main():
   io_objects_array = init_io_objects()
   for io_object in io_objects_array:
        print io_object.isOutput 

def read_from_db():
    db = MySQLdb.connect(hostname, username, password, database)
    cursor = db.cursor()
    cursor.execute("SELECT * FROM GPIO;")
    db.close()
    return(cursor.fetchall())

def init_io_objects():
    data = read_from_db()
    io_info = []
    for row in data:
        io_info.append(io(row))
    return io_info

main()




