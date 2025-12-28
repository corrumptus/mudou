export function formatDate(date) {
    const dayOfTheWeek = [
        "Domingo",
        "Segunda-feira",
        "Terça-feira",
        "Quarta-feira",
        "Quinta-feira",
        "Sexta-feira",
        "Sábado"
    ][date.getDay()];
    const day = date.getDate();
    const month = [
        "Janeiro",
        "Fevereiro",
        "Março",
        "Abril",
        "Maio",
        "Junho",
        "Julho",
        "Agosto",
        "Setembro",
        "Outubro",
        "Novembro",
        "Dezembro"
    ][date.getMonth()];
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, "0");
    const minutes = date.getMinutes().toString().padStart(2, "0");

    return `${dayOfTheWeek} - ${day} ${month} ${year} - ${hours}:${minutes}`;
}

export function timeDiff(date1, date2) {
    let diff = date2.getTime() - date1.getTime();
    diff = (diff - diff % 1000)/1000;

    const seconds = {
        name: "segundos",
        value: diff % 60
    };
    diff = (diff - seconds.value)/60;
    
    const minutes = {
        name: "minutos",
        value: diff % 60
    };
    diff = (diff - minutes.value)/60;
    
    const hours = {
        name: "horas",
        value: diff % 24
    };
    diff = (diff - hours.value)/24;

    const days = {
        name: "dias",
        value: diff % 30
    };
    diff = (diff - days.value)/30;

    const months = {
        name: "mêses",
        value: diff % 12
    };
    diff = (diff - months.value)/12;

    const years = {
        name: "anos",
        value: diff
    };

    const remainingTime = [years, months, days, hours, minutes, seconds]
        .filter(t => t.value > 0)
        .map(t => `${t.value} ${t.name}`)
        .join(", ");

    return remainingTime;
}