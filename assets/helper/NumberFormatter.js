export const format = (value) => {
  const formatterNumber = Intl.NumberFormat('pt-BR');

  return formatterNumber.format(value);
}

