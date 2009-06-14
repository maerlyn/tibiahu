$(document).ready(function() {
  doDataTables();
});


function doDataTables() {

  $(".levelhistory").dataTable({
    aaSorting: [],
    bFilter: false,
    bInfo: false,
    bPaginate: false,
    bProcessing: true,
    bStateSave: false
  });
  
  $(".searchresults").dataTable({
    aaSorting: [],
    bFilter: false,
    bInfo: false,
    bPaginate: false,
    bProcessing: true,
    bStateSave: false
  });

  $(".botterlist").dataTable({
    aaSorting: [],
    bFilter: false,
    bInfo: false,
    bPaginate: false,
    bProcessing: true,
    bStateSave: false
  });
  
}
