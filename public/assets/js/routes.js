/**
 * Calcula a data exata da rota no mês, ajustando fins de semana conforme a regra escolhida.
 * Equivalente à função em PHP que fizemos anteriormente.
 * 
 * @param {number} ano - Ex: 2026
 * @param {number} mes - Mês de 1 a 12
 * @param {number} diaFixo - Dia preferido do cliente (ex: 10)
 * @param {string} regra - 'PROXIMA_SEGUNDA', 'SEXTA_ANTERIOR' ou 'MANTER'
 * @returns {Date} Objeto Date ajustado
 */
function calcularDataRotaJS(ano, mes, diaFixo, regra = 'PROXIMA_SEGUNDA') {
    // No JS, os meses vão de 0 (Janeiro) a 11 (Dezembro). Por isso usamos mes - 1.
    // Para pegar o último dia do mês, passamos o dia 0 do mês seguinte.
    const ultimoDiaDoMes = new Date(ano, mes, 0).getDate();
    const diaValido = Math.min(diaFixo, ultimoDiaDoMes);

    let data = new Date(ano, mes - 1, diaValido);
    let diaSemana = data.getDay(); // 0 (Dom), 1 (Seg), ..., 6 (Sáb)

    if (regra === 'PROXIMA_SEGUNDA') {
        if (diaSemana === 6) { // Sábado -> +2 dias (Segunda-feira)
            data.setDate(data.getDate() + 2);
        } else if (diaSemana === 0) { // Domingo -> +1 dia (Segunda-feira)
            data.setDate(data.getDate() + 1);
        }
    } else if (regra === 'SEXTA_ANTERIOR') {
        if (diaSemana === 6) { // Sábado -> -1 dia (Sexta-feira)
            data.setDate(data.getDate() - 1);
        } else if (diaSemana === 0) { // Domingo -> -2 dias (Sexta-feira)
            data.setDate(data.getDate() - 2);
        }
    }

    return data;
}

/**
 * Função utilitária para formatar a data no padrão brasileiro (DD/MM/AAAA)
 */
function formatarDataBR(data) {
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    return `${dia}/${mes}/${ano}`;
}

// --- EXEMPLO DE USO ---
// Cliente que quer ser atendido todo dia 10:
// Em Dezembro de 2026 (dia 10 cai numa quinta-feira):
const dataDez = calcularDataRotaJS(2026, 12, 10);
console.log("Dezembro/2026:", formatarDataBR(dataDez)); // Saída: 10/12/2026

// Em Janeiro de 2027 (dia 10 cai num domingo, joga para segunda):
const dataJan = calcularDataRotaJS(2027, 1, 10);
console.log("Janeiro/2027:", formatarDataBR(dataJan)); // Saída: 11/01/2027