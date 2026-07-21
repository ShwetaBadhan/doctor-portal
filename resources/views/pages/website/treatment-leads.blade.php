@extends('layout.master')

@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">
            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Consultation List <span
                            class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">Total
                            Leads : {{ count($consultations) }}</span></h4>
                </div>

            </div>
            <!-- End Page Header -->

            <div class="table-responsive">
                <table class="table datatable table-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Message</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consultations as $consultation)
                            <tr>
                                <td>{{ $consultation->id }}</td>
                                <td>{{ $consultation->name }}</td>
                                <td>{{ $consultation->phone }}</td>
                                <td>{{ $consultation->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($consultation->date)->format('d-m-Y') }}</td>
                                <td>{{ $consultation->message }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>



    </div>
    </div>
@endsection
