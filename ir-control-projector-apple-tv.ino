#include <IRremote.h>
#include <SPI.h>
#include <Ethernet.h>

byte mac[] = { 0x00, 0x00, 0x00, 0x00, 0x00, 0x00 };
IPAddress ip(192, 168, 1, 00);
EthernetServer server(80);

IRsend irsend;

String HTTP_req;

void setup() {
    Ethernet.begin(mac, ip);
    server.begin();
    Serial.begin(9600);
}

void loop() {
    EthernetClient client = server.available();

    if (client) {
        boolean currentLineIsBlank = true;
    
        while (client.connected()) {
            if (client.available()) {
                char c = client.read();
                HTTP_req += c;
                String request = client.readStringUntil('\r');

                // APPLE TV
                if (request.indexOf("appleTVMenu") != -1) {
                  irsend.sendNEC(0x77E140B8, 32);
                } else if (request.indexOf("appleTVPlayPause") != -1) {
                  irsend.sendNEC(0x77E17AB8, 32);
                } else if (request.indexOf("appleTVUp") != -1) {
                  irsend.sendNEC(0x77E1D0B8, 32);
                } else if (request.indexOf("appleTVDown") != -1) {
                  irsend.sendNEC(0x77E1B0B8, 32);
                } else if (request.indexOf("appleTVLeft") != -1) {
                  irsend.sendNEC(0x77E110B8, 32);
                } else if (request.indexOf("appleTVRight") != -1) {
                  irsend.sendNEC(0x77E16057, 32);
                } else if (request.indexOf("appleTVSelect") != -1) {
                  irsend.sendNEC(0x77E1BAB8, 32);
        
                // BENQ PROJECTOR
                } else if (request.indexOf("projectorOn") != -1) {
                  irsend.sendNEC(0xCF20D, 32); 
                } else if (request.indexOf("projectorOff") != -1) {
                  irsend.sendNEC(0xC728D, 32); 
                  delay(500);
                  irsend.sendNEC(0xC728D, 32); 
                } else if (request.indexOf("projectorSource") != -1) {
                  irsend.sendNEC(0xC20DF, 32); 
                } else if (request.indexOf("projectorEnter") != -1) {
                  irsend.sendNEC(0xCA857, 32); 
                } else if (request.indexOf("projectorMenu") != -1) {
                  irsend.sendNEC(0xCF00F, 32); 
                } else if (request.indexOf("projectorUp") != -1) {
                  irsend.sendNEC(0xCD02F, 32); 
                } else if (request.indexOf("projectorDown") != -1) {
                  irsend.sendNEC(0xC30CF, 32); 
                } else if (request.indexOf("projectorLeft") != -1) {
                  irsend.sendNEC(0xCB04F, 32); 
                } else if (request.indexOf("projectorRight") != -1) {
                  irsend.sendNEC(0xC708F, 32); 
                } else if (request.indexOf("projectorEcoBlank") != -1) {
                  irsend.sendNEC(0xCE01F, 32); 
                } else if (request.indexOf("projectorBack") != -1) {
                  irsend.sendNEC(0xCA15E, 32); 
                } else if (request.indexOf("projectorMute") != -1) {
                  irsend.sendNEC(0xC28D7, 32); 
                } else if (request.indexOf("projectorVolumeUp") != -1) {
                  irsend.sendNEC(0xC41BE, 32); 
                } else if (request.indexOf("projectorVolumeDown") != -1) {
                  irsend.sendNEC(0xCC13E, 32); 
                } else if (request.indexOf("projectorKeystone") != -1) {
                  irsend.sendNEC(0xC54AB, 32); 
                } else if (request.indexOf("projectorMode") != -1) {
                  irsend.sendNEC(0xC08F7, 32); 
                } else if (request.indexOf("projectorBrightness") != -1) {
                  irsend.sendNEC(0xC6897, 32); 
                } else if (request.indexOf("projectorContrast") != -1) {
                  irsend.sendNEC(0xC8877, 32); 
                } else if (request.indexOf("projectorColorTemp") != -1) {
                  irsend.sendNEC(0xCFA05, 32); 
                } else if (request.indexOf("projectorColorTempFineTune") != -1) {
                  irsend.sendNEC(0xCBA45, 32); 
                } else if (request.indexOf("projectorGamma") != -1) {
                  irsend.sendNEC(0xC7A85, 32); 
                } else if (request.indexOf("projectorColorManage") != -1) {
                  irsend.sendNEC(0xCDA25, 32); 
                } else if (request.indexOf("projectorSharpness") != -1) {
                  irsend.sendNEC(0xC7E81, 32); 
                
                // FAN
                } else if (request.indexOf("fanTogglePower") != -1) {
                  unsigned int rawData[23] = {1200,450, 1200,450, 400,1200, 1250,400, 1200,450, 400,1250, 400,1250, 400,1250, 400,1250, 400,1250, 400,1250, 1200};  // UNKNOWN A32AB931  
                  irsend.sendRaw(rawData, sizeof(rawData) / sizeof(rawData[0]), 38);
                } else if (request.indexOf("fanChangeSpeed") != -1) {
                  unsigned int rawData[23] = {1250,400, 1250,400, 450,1150, 1300,400, 1200,400, 450,1200, 450,1200, 450,1200, 450,1200, 450,1200, 1250,400, 450};  // UNKNOWN 143226DB
                  irsend.sendRaw(rawData, sizeof(rawData) / sizeof(rawData[0]), 38);
                } else if (request.indexOf("fanToggleRotation") != -1) {
                  unsigned int rawData[23] = {1250,400, 1250,350, 500,1150, 1300,350, 1250,400, 450,1200, 450,1200, 1250,400, 450,1200, 450,1200, 450,1200, 450};  // UNKNOWN 39D41DC6    
                  irsend.sendRaw(rawData, sizeof(rawData) / sizeof(rawData[0]), 38);
                } else if (request.indexOf("fanToggleTimer") != -1) {
                  unsigned int rawData[23] = {1300,350, 1250,400, 450,1150, 1300,350, 1300,350, 450,1200, 450,1200, 450,1200, 1250,400, 450,1200, 450,1200, 450};  // UNKNOWN E0984BB6    i
                  irsend.sendRaw(rawData, sizeof(rawData) / sizeof(rawData[0]), 38);
                } else if (request.indexOf("fanToggleIonization") != -1) {
                  unsigned int  rawData[23] = {1300,350, 1250,400, 450,1200, 1250,350, 1300,350, 450,1200, 1250,400, 450,1200, 450,1200, 450,1200, 450,1200, 450};  // UNKNOWN 1D2FEFF6    
                  irsend.sendRaw(rawData, sizeof(rawData) / sizeof(rawData[0]), 38);
                }
                       
                if (c == '\n' && currentLineIsBlank) {
                  client.println("HTTP/1.1 200 OK");
                  client.println("Content-Type: text/html");
                  client.println("Connection: close");
                  client.println();
                  client.println("<html>");
                  client.println("<body>");
                  client.println("ready");
                  client.println("</body>");
                  client.println("</html>");
                  Serial.print(HTTP_req);
                  HTTP_req = "";
                  break;
                }
                
                if (c == '\n') { currentLineIsBlank = true; } else if (c != '\r') { currentLineIsBlank = false; }
            }
        }
        delay(1);
        client.stop();
    }
}
