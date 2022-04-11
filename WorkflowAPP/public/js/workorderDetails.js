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

/* Device */

$( '#deviceSearch' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: '/Device/searchDevice/' + req.term ,
           success:function(response){
               res(response);
               console.log(response);
        }
       }); 
    },
    minLength: 3,
    select:function(event,ui){
       console.log(ui.item);
       $('#device').val(ui.item.client_id);
       $('#manufacturer').val(ui.item.manufacturer);
       $('#model').val(ui.item.model);
       $('#serialnum').val([ui.item.serialnum].join(''));
       $('#deviceLabel').html(ui.item.manufacturer + ' ' + ui.item.model + ' ' + [ui.item.serialnum].join(''));
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
    return $( '<li>' )
      .append( '<div>' + item.manufacturer + ' ' + item.model + ' ' + [item.serialnum].join('') + '</div>')
      .appendTo( ul );
  };

$('#deviceSearch').on('keypress', function (e) {
    if(e.which !== 13){
        return;
    }
    let client = $('#deviceSearch').val().split(' ');
      if(client.length==0){
          return false;
      }
      $('#addDevice').foundation('reveal','open');
      

      
      console.log($('#deviceSearch').val());
    return false;
});