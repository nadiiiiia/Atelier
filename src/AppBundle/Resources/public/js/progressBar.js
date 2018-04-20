function progressBar(id, actual, max){
    var progressBar = $('#'+id+'');
    var percent = ((100 * parseFloat(actual)) / max);
    var percentVal = percent.toFixed();

    progressBar.css("width", percentVal+'%').attr("aria-valuenow", percentVal);
    
    if(percentVal < 50){
        progressBar.removeClass('bg-success');
        progressBar.removeClass('bg-danger');
        progressBar.addClass('bg-warning');
    }
    if(percentVal > 50 ){
        progressBar.removeClass('bg-warning');
        progressBar.removeClass('bg-danger');
        progressBar.addClass('bg-success');
    }

}