@extends('layouts.layout')
@section('title','Issue Report managements')
@section('content')
<div class="card">
    <div class="card-title">
        <h4>Queue Issue Reports</h4>
        <button type="button" class="btn btn-primary btn-flat btn-addon m-b-10 float-right" data-toggle="modal"
            data-target="#addIR">
            <span class="ti-plus"></span> Request new IR
        </button>
    </div>
    <div class="card-body">
        @if (session('sukses'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <strong>Successfull!</strong><br>
            <small>Laporan gangguan/dukungan sudah masuk kedalam <i>daftar antrian IR kami</i>.<br>
                <strong>IT/PU</strong>
                akan menyelesaikannya dalam waktu paling lama <strong>2x24 jam</strong>.<br> Jika laporan kamu tidak
                terselesaikan hubungi
                kami
                di ext.103 atau email kami di <a
                    href="mailto:support@btsa.co.id"><strong>support@btsa.co.id</strong></a>.</small>
        </div>
        @endif
        <div class="table-responsive">
            <table id="memberTables" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelapor</th>
                        <th>Antrian No.</th>
                        <th>Tujuan</th>
                        <th>Kendala</th>
                        <th>Status</th>
                        <th>Checked by</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$issueData->isEmpty())

                    @php $no = 1; @endphp
                    @foreach($issueData as $dt_issue)
                    <tr>
                        <th scope="row">{{$no++}}</th>
                        <td>{{$dt_issue->nama_lengkap}}</td>
                        <td><span class="badge badge-warning">{{$dt_issue->id}}</span></td>
                        <td><span class="badge badge-primary">{{$dt_issue->tujuan}}</span></td>
                        <td>{!!strip_tags($dt_issue->kendala)!!}</td>
                        <td class="text-center">
                            @if($dt_issue->status=='Selesai')
                            <span style="font-size: 1rem; color: green;"><i class="fas fa-check-circle"></i></span>
                            @elseif($dt_issue->status=='Belum Selesai')
                            <span style="font-size: 1rem; color: #e18a19;"><i class="fas fa-pause-circle"></i></span>
                            @else
                            <span style="font-size: 1rem; color: red;"><i class="fas fa-times-circle"></i></span>
                            @endif
                        </td>
                        <td>{{$dt_issue->approve}}</td>
                    </tr>
                    @endforeach
                    @else
                    <td colspan="7" class="text-center">No data founded!</td>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addIR" tabindex="-1" role="dialog" aria-labelledby="addIR" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/queue/addnew" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="addIR">Tambah IR Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="basic-elements">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nama_lengkap">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_lengkap"
                                            value="{{auth()->user()->nama_lengkap}}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kepada">Tujuan IR</label>
                                        <select name="tujuan" id="tujuan" class="form-control custom-select">
                                            <option value="it">IT</option>
                                            <option value="umum">Umum</option>
                                            <option value="hrd">HRD</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Laporkan kendalanya:</label>
                                        <textarea name="kendala" id="kendala" class="form-control" cols="30" rows="10"
                                            autofocus></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
