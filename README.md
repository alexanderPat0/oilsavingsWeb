# MANAGER

 email => admin.principal@google.com
 password => password123

# ADMINISTRADORES

email => administrador1@gmail.com
password => passwordadmin1

email => administrador2@gmail.com
password => passwordadmin2

# USUARIOS

usuariofinal@gmail.com
usuariofinal


firebase.js

import firebase from 'firebase/app';
import 'firebase/auth';

const firebaseConfig = {
    apiKey: "AIzaSyBmaXLlR-Pfgm1sfn-8oALHvu9Zf1fWT7k",
    authDomain: "oilsavings.firebaseapp.com",
    databaseURL: "https://oilsavings-default-rtdb.europe-west1.firebasedatabase.app",
    projectId: "oilsavings",
    storageBucket: "oilsavings.appspot.com",
    messagingSenderId: "160488075328",
    appId: "1:160488075328:web:95533d7bf75af4b2a6afa5",
    measurementId: "G-W9ZNG77RMT"
};

firebase.initializeApp(firebaseConfig);
export default firebase;
