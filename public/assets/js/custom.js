import express, { response } from 'express';
import Whatsapp from 'whatsapp-web.js';
const { Client, LocalAuth, MessageMedia } = Whatsapp;

let qrm = '';
let stat = false;

const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: 'public/assets/web/',
        clientId: 'enderman'
    })
});

const app = express()

client.on('qr', (qr) => {
    console.log('QR RECEIVED');
    qrm = qr;
});

client.on('ready', () => {
    stat = true;
    console.log('Client is ready!');
});

client.on('message', msg => {
    if (msg.body == 'hello') {
        msg.reply('hello x 2');
    }
});

async function deploy_all(req, res) {

    console.log(true);

    let media = await MessageMedia.fromUrl(req.body.path);
    const number = `91${req.body.number}@c.us`;
    const isRegistered = client.isRegisteredUser(number);
    const msg2 = client.sendMessage(number, media);
    console.log(msg2);
}

function logoutde() {
    client.logout(0);
    client.initialize();
    stat = false;
    return true;
}

client.initialize();

app.use(express.json());

app.get('/qr', function (req, res) {
    res.send({qrs: qrm, status: stat, session: 'jelly'})
})

app.post('/msg/:msg/number/:number', function(req, res) {
    deploy_all(req, res);
    res.send(true);
});

app.get('/logout', function(req, res) {
    logoutde();
    console.log(stat);
    res.send({status: stat});
})

app.post('/publish', (req, res) => {
  console.log(req.body.clientId);
  res.send('Data received');
});

app.post('/wamedida', function (req, res) {
    deploy_all(req, res);
    res.send(true);
})

app.listen(3000)