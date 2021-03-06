@extends('layouts.main')

@section('title')
    Admin -- home
@endsection

@section('content')
    @include('partials.admin_nav')
    <div class="page">
      <header class="header">
        <nav class="navbar"> 
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <div class="navbar-header"><a id="toggle-btn" href="#" class="menu-btn"><i class="icon-bars"> </i></a><a href="index.html" class="navbar-brand">
                  <div class="brand-text d-none d-md-inline-block"><strong class="text-primary">Dashboard</strong></div></a></div>
              <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                <!-- Notifications dropdown-->
                <li class="nav-item dropdown"> <a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
                  {{-- <i class="fa fa-bell"></i><span class="badge badge-warning" id="notifications">{{count($admin_notify)}}</span></a> --}}
                  @if (count($admin_notify) != 0)
                  <i class="fa fa-bell"></i><span class="badge badge-warning" id="notifications">{{count($admin_notify)}}</span></a>
                  
                  <ul aria-labelledby="notifications" class="dropdown-menu" id="notificationsMenu">
                    @foreach ($admin_notify as $item)
                    <li><a rel="nofollow" href="/admin/notify/{{$item->notify_id}}" class="dropdown-item"> 
                      <div class="notification d-flex justify-content-between">
                        <div class="notification-content"><i class="fa fa-envelope"></i>{{$item->message}} </div>
                        <div class="notification-time"><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}} </small></div>
                      </div></a></li>
                    
                    {{-- <li><a rel="nofollow" href="" class="dropdown-item all-notifications text-center"> <strong> <i class="fa fa-bell"></i> </strong></a></li> --}}
                    @endforeach
                   
                   
                  </ul>

                  @else 
                  <i class="fa fa-bell"></i><span class="badge badge-warning" id="notifications"></span></a>

                  @endif
                </li>
               
                <!-- Log out-->
                <li class="nav-item"><a href="{{route('logout')}}" class="nav-link logout"> <span class="d-none d-sm-inline-block">Logout</span><i class="fa fa-sign-out"></i></a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
    
      
    
        <div class="breadcrumb-holder">
            <div class="container-fluid">
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Home</li>
              </ul>
            </div>
          </div>

          <section class="statistics">
              <div class="container-fluid">
                  <header class="">

                  </header>

                  <div class="row d-flex">
                      {{-- total number of users --}}
                      <div class="col-lg-4">
                          <div class="card user-activity">
                            <h2 class="display h4">User Activity</h2>
                            <div class="number">{{$users}}</div>
                            <h3 class="h4 display">Total Users</h3>
                            <div class="progress">
                              <div role="progressbar" style="width:{{$users}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar bg-primary"></div>
                            </div>
                            
                          </div>
                          

                      </div>

                      <div class="col-lg-4">
                        <div class="card user-activity">
                          <h2 class="display h4">Transaction Activities</h2>
                         
                          <div class="input-group">
                            <div class="form-group col-md-6">
                              <div class="number">{{$loans}}</div>
                              <h3 class="h4 display">Loans</h3>
                            </div>
                            <div class="form-group">
                              <div class="number">{{$topup}}</div>
                              <h5 class="h5 display">Top-ups</h5>
                            </div>  
                          </div>
                          
                          <div class="progress">
                            <div role="progressbar" style="width:{{$total}} %"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar bg-primary"></div>
                          </div>
                          
                        </div>
                        

                    </div>

                    

                  </div>


                  <div class="row d-flex">
                    <div class="container">
                      <header>

                      </header>

                      <div class="col-lg-12">
                        <div class="card">
                          <div id="feeds-box" class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="h5 display">Transactions</h4>

                              <div class="right-column">
                                <select name="sort" id="sort" class="form-control">
                                  <option value="" selected>All Transactions</option>
                                  <option value="credit">Credit</option>
                                  <option value="debit">Debit</option>
                                </select>
                              </div>
                        </div>

                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table" id="transaction">
                              <thead>
                                <tr>
                                  <th>ID</th>
                                  <th>Transaction Type</th>
                                  <th>Transaction Name</th>
                                  <th>Transaction Amount</th>
                                  <th>Balance</th>
                                  <th>Status</th>
                                  <th>Date/time</th>
                                </tr>
                                
                              </thead>
                              
                          </div>

                        </div>
        
                        </div>
        
                      </div>

                    </div>
                    
      
                  </div>
              </div>

          </section>

          
    </div>
    
@endsection

@push('scripts')
<script src="{{asset('js/trans.js')}}"></script>
    
@endpush