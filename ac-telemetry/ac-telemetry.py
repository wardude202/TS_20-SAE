import socket
import logging
import struct
#import numpy as np

# Settings
ownIP = "0.0.0.0"
acIP = "10.0.0.66"
acPort = 9996
logLevel = logging.DEBUG

# AC PACKET CONSTANTS
HANDSHAKE = 0
SUBSCRIBE_UPDATE = 1
SUBSCRIBE_SPOT = 3
DISMISS = 3

# Global Variables
state = HANDSHAKE
connected = False

# Setup Logging
logging.basicConfig(format='%(levelname)s:%(message)s', level=logLevel)

# Setup UDP Socket
client = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
client.bind((ownIP, acPort))

# Send UDP Packet to Asetto Corsa Server
def send(op):
    msg = bytearray(12)
    msg.insert(0, 1) # identifier, 0 = eIPhoneDevice
    msg.insert(4, 1) # version
    msg.insert(8, op) # operation
    logging.debug(msg)
    client.sendto(msg, (acIP, acPort))

def readFloatLE(msg, offset):
    unpacked = struct.unpack_from("<f", msg, offset)
    return tuple(unpacked)[0]

def readFloatArr(msg, offset, num):
    res = list()
    for i in range(0, num):
        x = readFloatLE(msg, (offset + (4 * i)))
        res.insert(i, x)
    return res

def handleHandshakeMessage(msg, addr):
    global connected
    logging.info("Recieved Handshake")
    connected = True
    
    
def handleSubscribeUpdateMessage(msg, addr):
    logging.info("Recieved Subscribe Update Message")
    offset = 4
    speedKmh = readFloatLE(msg, offset * 2)
    speedMph = readFloatLE(msg, offset * 3)
    speedMs = readFloatLE(msg, offset * 4)
    accG_vertical = readFloatLE(msg, offset * 7)
    accG_horizontal = readFloatLE(msg, offset * 8)
    accG_frontal = readFloatLE(msg, offset * 9)
    gas = readFloatLE(msg, offset * 14)
    brake = readFloatLE(msg, offset * 15)
    clutch = readFloatLE(msg, offset * 16)
    engineRPM = readFloatLE(msg, offset * 17)
    steer = readFloatLE(msg, offset * 18)
    cgHeight = readFloatLE(msg, offset * 20)
    wheelAngularSpeed = readFloatArr(msg, offset * 21, 4)
    slipAngle = readFloatArr(msg, offset * 25, 4)
    slipAngle_ContactPatch = readFloatArr(msg, offset * 29, 4)
    slipRatio = readFloatArr(msg, offset * 33, 4)
    tyreSlip = readFloatArr(msg, offset * 37, 4)
    ndSlip = readFloatArr(msg, offset * 41, 4)
    load = readFloatArr(msg, offset * 45, 4)
    Dy = readFloatArr(msg, offset * 49, 4)
    Mz = readFloatArr(msg, offset * 53, 4)
    camberRAD = readFloatArr(msg, offset * 61, 4)
    tyreRadius = readFloatArr(msg, offset * 65, 4)
    tyreLoadRadius = readFloatArr(msg, offset * 69, 4)
    suspensionHeight = readFloatArr(msg, offset * 73, 4)
    carCoordinates = readFloatArr(msg, offset * 79, 3)
    
    

while True:        
    if (not connected):
        logging.info("Sending Handshake")
        send(HANDSHAKE)
        msg, addr = client.recvfrom(1024)
        handleHandshakeMessage(msg, addr)
    if connected:
        if (state == HANDSHAKE):
            logging.info("Sending Subscribe Update")
            send(SUBSCRIBE_UPDATE)
            state = SUBSCRIBE_UPDATE
        if (state == SUBSCRIBE_UPDATE):
            msg, addr = client.recvfrom(1024)
            handleSubscribeUpdateMessage(msg, addr)