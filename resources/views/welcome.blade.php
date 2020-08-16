<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Search-Product</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    </head>
    <body onload="getLocation()">
          
      @if (Session::has('invalid-details'))
      <p class="alert alert-danger"> {{Session::get('invalid-details')}}{{Session::put('invalid-details',null)}}</p>  
      @endif
            <center><br>
              <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <input  name="product_name" id="product_name" type="search" placeholder="Search" >&nbsp;&nbsp;
                <button id="btn" type="submit">Search</button>&nbsp;&nbsp;
                <select name="city" id="city">&nbsp;
                    <option>Indore</option>
                    <option>Bhopal</option>
                    <option>Mhow</option>
                    <option>Chennai</option>
                </select>
            </center>

              <h1 id="demo"><h1>
              <table>
                <tr>
                  <td><h2 id="name"></h2></td><td> &nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td><h2 id="cityname"></h2></td>
                </tr>
              </table>

          <script>
            let latitude, longitude, status = 0;
            function getLocation() {
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);

              } else { 
                x.innerHTML = "Geolocation is not supported by this browser.";
              }
            }
            
            function showPosition(position) {
                status = 1
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;
                // console.log(latitude)
                // console.log(longitude)
                let $city = document.getElementById('city')
                $city.disabled = true
            }
            $('document').ready(function(){
                $('#btn').click(function(){

                  $('#demo').text('')
                  $('#name').text('')
                  $('#cityname').text('')

                  let productName = $('#product_name').val()
                  let city = $('#city').val()
                  // console.log(productName)
                  // console.log(city)
                  if(productName == "" || city == "") {
                    alert("Please Enter Valid Information");
                  } else if (status != 1) {
                    // console.log('in else if ajax call')
                      $.ajax({
                        method: 'POST',
                        url: '/getproduct',
                        data: {
                          _token: $("#csrf").val(),
                          productName: productName,
                          city: city,
                          status: 0
                        },
                        cache: false,
                        success: function(data) {
                          // console.log('in success')
                          // console.log(data)
                          const result = JSON.parse(data)
                          // console.log(result)
                          if(result['statuscode'] == 200) {
                            const product = result['product'][0]
                            if(result['exact'] == true) {
                              $('#demo').text('Found at your location')
                              $('#name').text(product.product_name)
                              $('#cityname').text(product.city_name)
                            } else {
                              $('#demo').text('Found at another location')
                              $('#name').text(product.product_name)
                              $('#cityname').text(product.city_name)
                            }
                            
                          } else {
                            $('#demo').text(result['message'])
                          }
                        }
                      })
                  } else {
                    // console.log('in else ajax call')
                      $.ajax({
                        method: 'POST',
                        url: '/getproduct',
                        data: {
                          _token: $("#csrf").val(),
                          productName: productName,
                          latitude: latitude,
                          longitude: longitude,
                          status: 1
                        },
                        cache: false,
                        success: function(data) {
                          // console.log('in success')
                          // console.log(data)
                          const result = JSON.parse(data)
                          if(result['statuscode'] == 200) {
                            const product = result['product'][0]
                            if(result['exact'] == true) {
                              $('#demo').text('Found at your location')
                              $('#name').text(product.product_name)
                              $('#cityname').text(product.city_name)
                            } else {
                              $('#demo').text('Found at another location')
                              $('#name').text(product.product_name)
                              $('#cityname').text(product.city_name)
                            }
                            
                          } else {
                            $('#demo').text(result['message'])
                          }
                        }
                      })
                  }
                })
            })
        </script>
    </body>
   
</html>
