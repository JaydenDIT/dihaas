//date function to print readable date from timestamp string
function getDate(timestamp_string = null, withTime = false) {
    //withTime: whether time will be included or not 
    var date = timestamp_string == null ? new Date() : new Date(timestamp_string);
    var months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
    ];
    let dateTimeString = date.getDate() + " " + months[date.getMonth()] + ", " + date.getFullYear();
    if (withTime) {
        dateTimeString += ` ${date.getHours()}:${date.getMinutes()}`;
    }
    return dateTimeString;
}