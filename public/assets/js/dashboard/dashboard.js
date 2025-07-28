function update(){
    $('#ct').html(moment().format('D MMMM YYYY H:mm:ss'));
}
setInterval(update, 1000);
