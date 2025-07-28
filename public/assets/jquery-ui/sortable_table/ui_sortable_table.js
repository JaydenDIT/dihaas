/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function setSortable(){
    $( ".row_sortable_table tbody" ).sortable({
        connectWith: ".connectedSortable",
        start: function(event, ui) {
        },
        change: function(event, ui) {

        },
        update: function(event, ui) {
            $('.row_sortable_table tbody span').each(function(i)
            {
                $(this).html((i+1)+'. '); // sequence value for displaying table row after sorting
            });
        }
    }).disableSelection();
}
$(document).ready(function(){
     setSortable();
});


