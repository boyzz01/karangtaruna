@include('head')
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid page">
        @include('sidebar')
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            @include('topbar')
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
                    <div
                        class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                        <div class="d-flex align-items-center flex-wrap mr-2">
                            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Dashboard</h5>
                        </div>

                    </div>
                </div>
                <div class="d-flex flex-column-fluid">
                    <!--begin::Container-->
                    <div class="container">

                        <div class="row">
                            <div class="col-xl-6">
                                <!--begin::Stats Widget 15-->
                                <a href="{{ url('/') }}/admin/customer"
                                    class="card card-custom bg-success bg-hover-state-success card-stretch gutter-b">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                            <i class="fas fa-users icon-3x" style="color: white"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">
                                            {{ count($data) }}</div>
                                        <div class="font-weight-bold text-inverse-success font-size-sm">Total
                                            Anggota</div>
                                    </div>
                                    <!--end::Body-->
                                </a>
                                <!--end::Stats Widget 15-->
                            </div>


                            <div class="col-xl-6">
                                <!--begin::Stats Widget 15-->
                                <a href="#" class="card card-custom bg-info bg-hover-state-info card-stretch gutter-b">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                        <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                            <i class="fas fa-file-invoice icon-3x" style="color: white"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">
                                        </div>
                                        <div class="font-weight-bold text-inverse-success font-size-sm">Total
                                            UMKM</div>
                                    </div>
                                    <!--end::Body-->
                                </a>
                                <!--end::Stats Widget 15-->
                            </div>



                        </div>

                        <div class="card card-custom">
                            <div class="card-header flex-wrap py-5">
                                <div class="card-title">
                                    <h3 class="card-label">List Anggota
                                        <span class="text-muted pt-2 font-size-sm d-block"></span>
                                    </h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Dropdown-->

                                    <!--end::Dropdown-->
                                    <!--begin::Button-->

                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <table class="table table-bordered table-checkable " id="kt_datatable">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th>Nama</th>
                                            <th>KTA</th>
                                            <th>KTP</th>
                                            <th>NO HP</th>
                                            <th>Aksi</th>


                                        </tr>
                                    </thead>
                                    <tbody>


                                        @php
                                            $nomor = 1;
                                        @endphp
                                        @foreach ($data as $d)
                                            <tr id="tr{{ $d->id }}">
                                                <td style=" width:5%">{{ $nomor }}</td>
                                                <td>{{ $d->nama }}</td>
                                                <td>{{ $d->kta }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->no_hp }}</td>
                                                <td nowrap="nowrap">


                                                    <a href="javascript:;" class="btn btn-sm btn-info btn-icon edit_btn"
                                                        id="{{ $d->id }}" title=" Edit Data Pasien">
                                                        <i class="la la-edit"></i>
                                                    </a>



                                                    <button type="submit" class="btn btn-sm btn-danger btn-icon"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure want to delete this data?')"><i
                                                            class="la la-trash">
                                                        </i>
                                                    </button>

                                                </td>



                                            </tr>

                                            @php
                                                $nomor++;
                                            @endphp
                                        @endforeach




                                    </tbody>
                                </table>
                                <!--end: Datatable-->
                            </div>
                        </div>
                    </div>
                    <!--end::Container-->
                </div>
            </div>
            @include('footer')
