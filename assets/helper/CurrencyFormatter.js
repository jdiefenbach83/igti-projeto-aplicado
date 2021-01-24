export const format = (value) => {
  const formatterCurrency = Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  });

  return formatterCurrency.format(value);
}

