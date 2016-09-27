import RPi.GPIO as GPIO
import MySQLdb
from login import *
import time

io_objects_array = []
db_is_being_accessed = False;

class io:
    def __init__(self, db_row):
        self.pin_num = int(db_row[0] + 1)
        
        self.isOutput = db_row[2]
        #Set the previous value to the opposite state to force an update
        if self.isOutput == 0:
            self.isOutputPrevious = 1 
        else :
            self.isOutputPrevious = 0
        
        self.isHigh = db_row[3]
        #Set the previous value to the opposite state to force an update
        if self.isHigh == 0:
            self.isHighPrevious = 1
        else: 
            self.isHighPrevious = 0

    def update(self, db_row):
        self.pin_num = int(db_row[0] + 1)
        self.isOutput = db_row[2]
        if self.isOutput == 1: #Only update the isHigh property if configured as output
            self.isHigh = db_row[3]

def read_from_db():
    global db
    db = MySQLdb.connect(hostname, username, password, database)
    db_is_being_accessed = True
    cursor = db.cursor()
    cursor.execute("SELECT * FROM GPIO;")
    db.close()
    db_is_being_accessed = False
    return(cursor.fetchall())

def update_io_objects():
    data = read_from_db()
    if (len(io_objects_array) == 0): # Check if the io_objects_array is initialized
        for i in range(0, len(data)):
            io_objects_array.append(io(data[i]))
    elif (len(io_objects_array) == len(data)): # Update the io_objects_array from the database
        for i in range(0, len(data)):
            io_objects_array[i].update(data[i])
    else:
        # This should NEVER occur unless the table has suffered an unexpected resize
        print "ERROR: size mismatch between database table and io_objects_array"

def update_gpio_setmodes():
    for io_object in io_objects_array:
        if io_object.isOutput != io_object.isOutputPrevious:
            io_object.isOutputPrevious = io_object.isOutput
            print("Pin direction has changed for GPIO%d." % io_object.pin_num)
            if io_object.isOutput:
                GPIO.setup(io_object.pin_num, GPIO.OUT)
            else:
                GPIO.setup(io_object.pin_num, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

def update_gpio_output_states():
    for io_object in io_objects_array:
        if (io_object.isOutput == 1) and (io_object.isHigh != io_object.isHighPrevious):
            io_object.isHighPrevious = io_object.isHigh
            print("GPIO%d output has changed state." % io_object.pin_num)
            if io_object.isHigh:
                GPIO.output(io_object.pin_num, GPIO.HIGH)
            else:
                GPIO.output(io_object.pin_num, GPIO.LOW)

def read_gpio_input_states():
    global db
    db = MySQLdb.connect(hostname, username, password, database)
    db_is_being_accessed = True
    cursor = db.cursor()
    
    for io_object in io_objects_array:
        if io_object.isOutput == 0:
            if GPIO.input(io_object.pin_num):
                io_object.isHigh = 1
            else:
                io_object.isHigh = 0
            query = """UPDATE GPIO SET isHigh=%d WHERE id=%d;""" % (io_object.isHigh, (io_object.pin_num-1))
            cursor.execute(query)
            db.commit()

    db.close()
    db_is_being_accessed = False
                    
def main():
    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BCM)
    #try:
    while 1:
        update_io_objects()
        update_gpio_setmodes()
        update_gpio_output_states()
        read_gpio_input_states()

    #except:
        #GPIO.cleanup()
        #if db_is_being_accessed:
            #db.close()

main()




