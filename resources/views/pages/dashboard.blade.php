@extends('layout.master')
@section('content')
 <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content pb-0">

                <!-- Page Header -->
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Admin Dashboard </h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                       <a href="{{ route('appointments.create') }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-plus me-1"></i>New Appointment</a>
                       
                    </div>
				</div>
				<!-- End Page Header -->

                <!-- start row -->
                <div class="row">
                @include('components.dashboard.stats')
                </div>
                <!-- end row -->

                <!-- row start -->
                <div class="row">
                  @include('components.dashboard.graph')
                </div>
                <!-- end row -->

                

                <!-- row start -->
                <div class="row">
                   @include('components.dashboard.appointments-stats')
                </div>
                <!-- row end -->

             
                                
            </div>
            <!-- End Content -->

           @include('components.copyright')

        </div>
@endsection