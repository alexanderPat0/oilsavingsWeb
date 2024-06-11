import express from 'express';
const app = express();

const PORT = process.env.PORT || 3000;
// 
app.get('/', (req, res) => {
  res.status(200).send('OK');
});

app.listen(PORT, () => {
  console.log(`Aplicación ejecutándose en el puerto ${PORT}`);
});