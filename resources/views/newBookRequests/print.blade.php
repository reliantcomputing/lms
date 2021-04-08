<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Printing</title>
    <style>
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
              border: 1px solid #dddddd;
            }
            th, td{
                border: 1px solid #dddddd; 
            }
            
            body{
                font-family: arial, sans-serif;
            }
        </style>
</head>
<body>
        <h1>{{$title}}</h1>
        <hr>
        @php
            $count = 0;
        @endphp
        @foreach ($orders as $order)
        @php
            $count++;
        @endphp
        <div class="data-table-area">
            <div class="data-table-list">
                    <h4>{{$count.". "}}New Book Details</h4>
                    <p><b>Title:</b> {{$order->title}}</p>
                    <p><b>ISBN:</b> {{$order->isbn_number}}</p>
                    <p><b>Author:</b> {{$order->author}}</p>
                    <p><b>Place of publication:</b> {{$order->place_of_publication}}</p>
                    <p><b>Department:</b> {{$order->department->name}}</p>
                    <p><b>Edition:</b> {{$order->edition}}th</p>
                    @if ($order->price)
                        <p><b>Price:</b> {{$order->price}}</p> 
                    @endif

                    <br>
        
                    <table style="width:100%">
                            <caption>Request Details</caption>
                            <br>
                            <tr>
                              <th><b>Copies</b></th>
                              <th><b>Requested on</b> </th>
                              <th><b>Requested by</b></th>
                              <th><b>Processed by</b></th>
                              <th><b>Status</b></th>
                              @if ($order->price)
                              <th><b>Total Cost(ZAR)</b></th>
                              @endif
                              
                            </tr>
                            <tr>
                                <td>{{strval($order->quantity)}}</td>
                                <td>{{$order->created_at}}</td>
                                <td>{{strval($order->staff_number)}}</td>
                                @if ($order->librarian_number == null)
                                    Not processed
                                @else
                                    <td>{{strval($order->librarian_number)}}</td>
                                @endif
                                <td>{{$order->status}}</td>
                                @if ($order->book())
                                    @if ($order->price)
                                        <td>R{{$order->book()->stock_price * $order->quantity}}</td>
                                    @endif
                                @else
                                    Not proccessed.
                                @endif
                                
                            </tr>
                    </table>
            </div>
        </div> 
        <hr>
        @endforeach
</body>
</html>




