export const formatBrazilianDate = (date) => {
  if (!date) return null;

  const [year, month, day] = date.split('-');
  return `${day}/${month}/${year}`;
}

export const formatISODate = (date) => {
  if (!date) return null;

  const [day, month, year] = date.split('/');
  return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
}

export const getNewISODate = () => {
  return new Date().toISOString().substr(0, 10);
}

export const getNewBrazilianDate = () => {
  return formatBrazilianDate(getNewISODate());
}