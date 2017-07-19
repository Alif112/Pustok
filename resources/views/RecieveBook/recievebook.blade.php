@extends('layouts.app')
@section('content')




    <div class="container">




        <!-- You only need this form and the form-labels-on-top.css -->

        @if(Auth::user()->user_type!='normal')
        <style type="text/css">
            .form-group {
                margin: 20px;
            }

            .tet{
                text-align: center;
            }


        </style>


            <form class="form-horizontal" method="POST" action="/recievebook">

            {{csrf_field()}}
              <fieldset>
                <legend class="tet">Recieve Book</legend>
                <div class="form-group">
                  <label for="inputEmail" class="col-lg-5 control-label">Book Access No:</label>
                  <div class="col-lg-5">
                    <input type="text" name="book_id" class="form-control" id="inputEmail" placeholder="Book id Number">
                  </div>
                  </div>

                <div class="form-group">
                  <div class="col-lg-12 col-lg-offset-5">
                    <button type="submit" class="btn btn-primary">Receive</button>
                  </div>
                </div>
              </fieldset>
            </form>





        
          <!--   <form class="form-labels-on-top" method="POST" action="/recievebook">

                {{ csrf_field() }}

                <div class="form-title-row">
                    <h1>Recieve Book</h1>
                </div>

                
                <div class="form-row">
                    <label>
                        <span>Book Access No: </span>
                        <input type="text" name="book_id" required="">
                    </label>
                </div>

                <div class="form-row">
                    <button type="submit">Book Recieved</button>
                </div>

            </form> -->

            @if($request!=NULL)
                
                <!-- TEST -->

                <h2> request for recieved </h2>

                <!-- BOOK-->
                <?php

                    $temp_book = DB::table('books')->where('id', $request->book_id)->first();
                ?>

                <!-- VERIFY THE BOOK -->
                
                @if($temp_book==NULL)
                    <h2> No Book for this serial number </h2>
                @else
                    <?php
                        $temp_loan = DB::table('loans')->where('bookid', $temp_book->id)->where('retturn', 1)->first();
                    ?>
                    <!-- CHECK IF THAT BOOK IS TAKEN OR NOT -->
                    @if( $temp_loan==NULL)
                        <h2> This book is not taken any students </h2>
                    @elseif($temp_loan->retturn==0)
                        <h2> This book is not taken any students </h2>                    
                    <!-- OTHERWISE RECIEVE THIS BOOK -->
                    @else
                    <?php
                        
                        $temp_loan = DB::table('loans')->where('bookid', $temp_book->id)->first();
                        $temp_user = DB::table('users')->where('regno', $temp_loan->user)->first();
                        // UPDATE IN USER TABLE

                        if($temp_user->loan_number1==$temp_loan->loan_number)
                        {
                            DB::table('users')
                                ->where('regno', $temp_loan->user)
                                ->update(['loan_number1' => 0 ]);
                            DB::table('loans')
                                ->where('bookid', $temp_book->id)->where('retturn', 1)
                                ->update(['retturn' => 0 ]);
                            DB::table('books')
                                ->where('id', $temp_book->id)->update(['loan_number' => 0 ]);
                        }
                        else
                        {
                            DB::table('users')
                                ->where('regno', $temp_loan->user)
                                ->update(['loan_number2' => 0]);
                            DB::table('loans')
                                ->where('bookid', $temp_book->id)->where('retturn', 1)
                                ->update(['retturn' => 0 ]);
                            DB::table('books')
                                ->where('id', $temp_book->id)->update(['loan_number' => 0 ]);
                        }
                    ?>

                    <h2> Book Recieved </h2>
                    @endif

                @endif


                
            @endif

        @else

            <div class="form-row">
                <label>
                    <span>No access</span>
                </label>
            </div>


        @endif

        

    </div>

</body>


@endsection
