/* Client */

$( '#clientSearch' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: '/Client/searchClient/' + req.term ,
           success:function(response){
               res(response);
               console.log(response);
        }
       }); 
    },
    minLength: 3,
    select:function(event,ui){
       console.log(ui.item);
       $('#client').val(ui.item.client_id);
       $('#firstname').val(ui.item.firstname);
       $('#lastname').val(ui.item.lastname);
       $('#company').val([ui.item.company].join(''));
       $('#clientLabel').html(ui.item.firstname + ' ' + ui.item.lastname + ' ' + [ui.item.company].join(''));
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div>' + item.firstname + ' ' + item.lastname + ' ' + [item.company].join('') + '</div>')
      .appendTo( ul );
  };

$('#clientSearch').on('keypress', function (e) {
    if(e.which !== 13){
        return;
    }
    let client = $('#clientSearch').val().split(' ');
      if(client.length==0){
          return false;
      }
      $('#addClient').foundation('reveal','open');
      

      
      console.log($('#clientSearch').val());
    return false;
});