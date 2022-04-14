/* Client */

$( '#clientSearch' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: '/Client/searchClient/' + req.term ,
           success:function(response){
               if(response.length===0){
                    $('#addclient').foundation('open');
               }else{
                res(response);
               }
            }
       }); 
    },
    minLength: 2,
    select:function(event,ui){
       console.log(ui.item);
       $('#client').val(ui.item.client_id);
       $('#firstname').val(ui.item.firstname);
       $('#lastname').val(ui.item.lastname);
       $('#company').val([ui.item.company].join(''));
       $('#clientLabel').html(ui.item.firstname + ' ' + ui.item.lastname + ' ' + [ui.item.company].join(''));
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ){
    return $( '<li>' )
      .append( '<div>' + item.firstname + ' ' + item.lastname + ' ' + [item.company].join('') + '</div>')
      .appendTo( ul );
  };

$('#addnewclient').click(function(){
    $.ajax({
        type: "POST",
        url:'/Client/addclient',
        data: {
            firstname: $('#wmfirstname').val(),
            lastname: $('#wmlastname').val(),
            company: $('#wmcompany').val(),
            phonenum: $('#wmphonenum').val(),
            email: $('#wmemail').val()
        },
        success:function(response){
            console.log(response);
            $('#client').val(response);
            $('#clientLabel').html($('#wmfirstname').val() + ' ' + $('#wmlastname').val() + ' ' + $('#wmcompany').val());
            $('#firstname').html($('#wmfirstname').val());
            $('#lastname').html($('#wmlastname').val());
            $('#company').html($('#wmcompany').val());
            $('#addclient').foundation('close');
        }
    });
    return false;
});

/* Device */

$( '#deviceSearch' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: '/Device/searchDevice/' + req.term ,
           success:function(response){
               if(response.length===0){
                    $('#adddevice').foundation('open');
               }else{
                res(response);
               }
            }
       }); 
    },
    minLength: 2,
    select:function(event,ui){
       console.log(ui.item);
       $('#device').val(ui.item.device_id);
       $('#manufacturer').val(ui.item.manufacturer);
       $('#model').val(ui.item.model);
       $('#serialnum').val([ui.item.serialnum].join(''));
       $('#deviceLabel').html(ui.item.manufacturer + ' ' + ui.item.model + ' ' + [ui.item.serialnum].join(''));
    }
}).autocomplete( 'instance' )._renderItem = function( ul, item ){
    return $( '<li>' )
      .append( '<div>' + item.manufacturer + ' ' + item.model + ' ' + [item.serialnum].join('') + '</div>')
      .appendTo( ul );
  };

$('#addnewdevice').click(function(){
    $.ajax({
        type: "POST",
        url:'/Device/adddevice',
        data: {
            client: $('#client').val(),
            manufacturer: $('#wmmanufacturer').val(),
            model: $('#wmmodel').val(),
            serialnum: $('#wmserialnum').val()
        },
        success:function(response){
            console.log(response);
            $('#device').val(response);
            $('#deviceLabel').html($('#wmmanufacturer').val() + ' ' + $('#wmmodel').val() + ' ' + $('#wmserialnum').val());
            $('#manufacturer').html($('#wmmanufacturer').val());
            $('#model').html($('#wmmodel').val());
            $('#serialnum').html($('#dmcomwmserialnumpany').val());
            $('#adddevice').foundation('close');
        }
    });
    return false;
});