

var user_id

function onLoad(){
  //get data

  axios({
    method:'get',
    url:'https://my-crud-operation.herokuapp.com/API/fetch_all.php'
  })
  .then(function(datas){
    $('.display-loader').addClass('d-none');

    var users={
      data:datas.data.data
    }

    //render
    template=$('#table').html()
    html=Mustache.render(template,users)
    $('.tbody').html(html)

    //edit
    $('.edit').click(function(){
      firstname=$(this).parent().parent().children()[0].innerText
      lastname=$(this).parent().parent().children()[1].innerText
      user_id=$(this).parent().parent().attr('id')
      $('.modal-title').html('edit')
      $('#userForm').find('#FirstName').val(firstname)
      $('#userForm').find('#LastName').val(lastname)
      $('#add').html('update')
      $('#modal').modal('show')
    })

    //onClick Delete
    $('.delete').click(function(){
      if(confirm('Are you sure you want to delete this person')){
        var id=$(this).parent().parent().attr('id')
        deletePerson(id)
      }
    })

  })
  .catch(function(error){
    console.log(console.error.response)
  })
}

function add(fname,lname){
  axios({
    method:'post',
    url:'https://my-crud-operation.herokuapp.com/API/add_edit.php',
    data:{
          "function":"add",
          "firstname":fname,
          "lastname":lname
        }
      })
      .then(function(data){
        console.log(data);
        $('#modal').modal('toggle')
        if(data.data.status==411){
          alert('FirstName and lastName exists')
        }
        else{
            alert('User added successfully')
        }
        $('#userForm').trigger('reset')
        onLoad()
      })
}

function edit(fname,lname,user_id){
  axios({
    method:'post',
    url:'https://my-crud-operation.herokuapp.com/API/add_edit.php',
    data:{
          "function":"edit",
          "firstname":fname,
          "lastname":lname,
          "user_id":user_id
        }
      })
      .then(function(data){
        console.log(data);
        $('#modal').modal('toggle')
        alert('Your Changes have been saved')
        $('#userForm').trigger('reset')
        onLoad()
      })
}

function deletePerson(user_id){
  axios({
    method:'post',
    url:'https://my-crud-operation.herokuapp.com/API/delete.php',
    data:{
          id:user_id
    }
  })
  .then(function(data){
    console.log(data);
    alert('user removed successfully')
    onLoad()
  })
  .catch(function(){
    alert('An error occured')
  })
}

  $(document).ready(function(){
    FirstName=$('#FirstName')
    LastName=$('#LastName')
    onLoad()


    //hide <small>
    $('small').hide()

    //show Modal
    $('#add_modal').click(function(){
      $('.modal-title').html('add')
      $('#add').html('add')
      $('#userForm').trigger('reset')
      $('#modal').modal('show')
    })

    //Is valid display
    $('input[type="text"]').focus(function(){
      if(!($(this).val())){
        $(this).next().show()
      }
    })
    $('input[type="text"]').keyup(function(){
      if($(this).val()){
        $(this).next().hide()
      }
    })

    //Onclick Validate
    $('#add').click(function(){

      if(!FirstName.val()){
        FirstName.focus()
        FirstName.next().show()
      }
      else if(!LastName.val()){
        LastName.focus()
        LastName.next().show()
      }

      else if($(this).html()=='add'){
          add(FirstName.val(),LastName.val())
      }

      else{
        edit(FirstName.val(),LastName.val(),user_id)
      }
    })
  })
