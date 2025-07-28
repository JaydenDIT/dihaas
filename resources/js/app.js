require('./bootstrap');
$(document).ready(function () {
    // functions show and hide all empployee,  active employee list and removed employee list 
    $('#all_emp_list_btn').click(function () {
        $('#all_emp_table').show();
        $('#active_emp_table').hide();
        $('#removed_emp_table').hide();

    });
    $('#active_list_btn').click(function () {
        $('#all_emp_table').hide();
        $('#active_emp_table').show();
        $('#removed_emp_table').hide();

    });
    $('#removed_list_btn').click(function () {
        $('#all_emp_table').hide();
        $('#active_emp_table').hide();
        $('#removed_emp_table').show();

    });
    // end.......................................................................................
    // start button ...............................

    $('#start_emp').click(function () {
        $('#view_emp').show();
        $('#start_emp').hide();
    });
    // ........................................Form.............................................

    
    
});