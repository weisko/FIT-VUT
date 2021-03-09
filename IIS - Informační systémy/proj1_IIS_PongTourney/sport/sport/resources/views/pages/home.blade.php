@extends('layouts.app')

@section('content')    
<!DOCTYPE html>
<head>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style>
  .MyJumbotron{
    position: top;
    background-color:rgba(0, 0, 0, 0.3);
    color:tomato;
    min-height: 60vh;
    margin:auto;
    padding-top: 0px;
    padding-bottom: 0px;
  }
  .MyTabList .MyTabHeader.active{
    font-weight:bold;
    color: aliceblue;
    background: linear-gradient(45deg, #EB3349,  #F45C43);
    border-bottom:3px solid #F9DC5C;
    border-right: none;
    border-left: none;
    border-top: none;
    transition: cubic-bezier(0.25, 0.8, 0.5, 0.5)
  }
  .HeaderText{
    padding-top: 13px;
    text-align: center;
    font-size: 8vw;
    background: -webkit-linear-gradient(45deg, #EB3349,  #F45C43);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .JumboText{
    text-align: center;
    font-size: 1.9vw;
    background: -webkit-linear-gradient(45deg, #EB3349,  #F45C43);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .JumboTabText{
    text-align: left;
    margin-bottom: 0px;
    margin-top: 22vh; 
    font-size: 3.4vw;
    background: -webkit-linear-gradient(45.34deg, #FBB13C, #FF7A72, #FF7A72);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .MyTabs{
    width: 100%;
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
  }
  .MyTabHeader{
    background-color: black;
    border-bottom: 3px solid groove;
    color: aliceblue;
    font-weight:bold;
    font-size: 23px;
  }
  .MyTabHeader:hover{
    color: #EF4446;
  }
  .MyTabContent{
    min-height: 20.9345vh;
    background-color: #666666;
  }
  .MyHeaderCell{
    color: white;
    font-size: 22px;
  }
  .MyDataCell{
    color: white;
    font-size: 18px;
  }
  .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
    background-color: rgba(244, 89, 68, .4)
  }
  
</style>

{{-- <div class="card MyCard">
    WTF IS HERE
</div> --}}
<div class="animated fadeIn jumbotron jumbotron-fluid MyJumbotron">
    <div class="container-fluid">
        <h1 class=" HeaderText">WELCOME</h1>
        <p class="JumboText"> Please register to participate in tournaments</p>
        <p class="JumboTabText">Upcoming tournaments:</p>
    </div>
</div>

<section class="animated slideInUp fast MyTabs">
  <div class="MyContainer">
    <div class="col-md-12 text-center MyTabs">
        <nav class="nav-justified MyTabNav">
          <div class="nav MyTabList" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active MyTabHeader" id="pop1-tab" data-toggle="tab" href="#pop1" role="tab" aria-controls="pop1" aria-selected="true">Singles</a>
            <a class="nav-item nav-link MyTabHeader" id="pop2-tab" data-toggle="tab" href="#pop2" role="tab" aria-controls="pop2" aria-selected="false">Doubles</a>
          </div>
        </nav>
        <div class="tab-content MyTabContent" id="nav-tabContent">
          <div class="tab-pane fade show active" id="pop1" role="tabpanel" aria-labelledby="pop1-tab">
            <div class="pt"></div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th class="MyHeaderCell" scope="col">Name</th>
                    <th class="MyHeaderCell" scope="col">Date</th>
                    <th class="MyHeaderCell" scope="col">Location</th>
                    <th class="MyHeaderCell" scope="col">Teams</th>
                    <th class="MyHeaderCell" scope="col">Entry</th>
                    <th class="MyHeaderCell" scope="col">Prize</th>
                  </tr>
                </thead>
                <tbody>
                    @if(count($tournaments) > 0)
                        @foreach ($tournaments as $tournament)
                        <tr>
                          @if (($tournament->type) == "Individual")
                            @if (($tournament->date) >= date("dd.mm.YYYY",strtotime($tournament->date)))
                              <th class="MyDataCell" scope="row">{{$tournament->name}}</th>
                              <td class="MyDataCell">{{$tournament->date}}</td>
                              <td class="MyDataCell">{{$tournament->place}}</td>
                              <td class="MyDataCell">{{$tournament->maximum_teams}}</td>
                              <td class="MyDataCell">{{$tournament->registration_fee}}€</td>
                              <td class="MyDataCell">{{$tournament->winning_prize}}€</td>
                            @endif
                          @endif
                        </tr>
                        @endforeach
                      @endif
                </tbody>
              </table>
            </div>
          <div class="tab-pane fade" id="pop2" role="tabpanel" aria-labelledby="pop2-tab">
            <div class="pt"></div>
            <table class="table">
              <thead>
                <tr>
                  <th class="MyHeaderCell" scope="col">Name</th>
                  <th class="MyHeaderCell" scope="col">Date</th>
                  <th class="MyHeaderCell" scope="col">Location</th>
                  <th class="MyHeaderCell" scope="col">Teams</th>
                  <th class="MyHeaderCell" scope="col">Entry</th>
                  <th class="MyHeaderCell" scope="col">Prize</th>
                </tr>
              </thead>
              <tbody>
                        
                  @if(count($tournaments) > 0)
                      @foreach ($tournaments as $tournament)
                      <tr> 
                        @if (($tournament->type) == "Teams")
                          @if (($tournament->date) >= date("dd.mm.YYYY",strtotime($tournament->date)))
                            <th class="MyDataCell" scope="row">{{$tournament->name}}</th>
                            <td class="MyDataCell">{{$tournament->date}}</td>
                            <td class="MyDataCell">{{$tournament->place}}</td>
                            <td class="MyDataCell">{{$tournament->maximum_teams}}</td>
                            <td class="MyDataCell">{{$tournament->registration_fee}}€</td>
                            <td class="MyDataCell">{{$tournament->winning_prize}}€</td>
                          @endif
                        @endif 
                      </tr>
                      @endforeach
                    @endif
                
              </tbody>
            </table>
          </div>                
        </div>
    </div>
  </div>
</section>

<script>
  nav-justified
</script>

@endsection
