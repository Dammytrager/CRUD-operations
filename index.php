<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>CRUD Practice</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src='bootstrap/jquery/jquery.min.js'></script>
    <script type="text/javascript" src='bootstrap/js/bootstrap.min.js'></script>
    <script type="text/javascript" src='axios.min.js'></script>
    <script type="text/javascript" src='mustache.js'></script>
  </head>
  <body>

    <!--table template-->
    <template id="table">
      {{#data}}
      <tr id={{user_id}}>
        <td>{{firstname}}</td>
        <td>{{lastname}}</td>
        <td><button type="button" class='btn btn-warning edit' name="button">Edit</button></td>
        <td><button type="button" class='btn btn-danger delete' name="button">Delete</button></td>
      </tr>
      {{/data}}
    </template>

    <div class="container">
      <h2 class='text-center' style='margin-bottom:30px;'>My CRUD API Practice</h2>
      <table class='table table-striped'>
        <thead>
          <tr>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody class='tbody'>

        </tbody>
      </table>
      <div class="form-group">
        <button type="btn" class='btn btn-success' id='add_modal' name="button">Add</button>
      </div>

        <!--Modal -->
        <div class='modal fade' id='modal'>
          <div class='modal-dialog'>
            <div class='modal-content'>

              <!--header-->
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                  <h3 class='modal-title text-center'>Add</h3>
              </div>

              <!--Body-->
              <div class='modal-body'>
                <form class='form' id='userForm'>
                  <div class='form-group'>
                    <input type='text' class='form-control' placeholder='FirstName' id='FirstName'>
                    <small class='text-danger'><em>This field is required</em></small>
                  </div>

                  <div class='form-group'>
                    <input type='text' class='form-control' placeholder='LastName' id='LastName'>
                    <small class='text-danger'><em>This field is required</em></small>
                  </div>
                </form>
              </div>

              <!--footer-->
              <div class='modal-footer'>
                <button type='button' id='add' class='btn btn-success btn-block'>Add</button>
              </div>

            </div>
          </div>
        </div>
    </div>


    <script type="text/javascript">

    var user_id

    function onLoad(){
      //get data

      axios({
        method:'post',
        url:'API/fetch_all.php'
      })
      .then(function(datas){
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
        url:'API/add_edit.php',
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
        url:'API/add_edit.php',
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
        url:'API/delete.php',
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


    </script>
  </body>
</html>
