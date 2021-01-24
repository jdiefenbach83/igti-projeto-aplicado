const addZero = (numero) => {
  if (numero <= 9)
    return '0' + numero;

  return numero;
}

export const format = (date) => {
  const new_date = new Date(date);

  const year = new_date.getFullYear();
  const month = addZero(new_date.getMonth() +1);
  const day = addZero(new_date.getDate() +1);

  return `${day}/${month}/${year}`;
}