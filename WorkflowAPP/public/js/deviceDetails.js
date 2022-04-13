/* Client */

$( '#clientSearch' ).autocomplete({
    source: function(req,res){
       $.ajax({
           url: '/Client/searchClient/' + req.term ,
           success:function(response){
               if(response.length===0){
                    $('#details').foundation('open');
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
            firstname: $('#dmfirstname').val(),
            lastname: $('#dmlastname').val(),
            company: $('#dmcompany').val(),
            phonenum: $('#dmphonenum').val(),
            email: $('#dmemail').val()
        },
        success:function(response){
            console.log(response);
            $('#client').val(response);
            $('#clientLabel').html($('#dmfirstname').val() + ' ' + $('#dmlastname').val() + ' ' + $('#dmcompany').val());
            $('#firstname').html($('#dmfirstname').val());
            $('#lastname').html($('#dmlastname').val());
            $('#company').html($('#dmcompany').val());
            $('#details').foundation('close');
        }
    });
    return false;
});