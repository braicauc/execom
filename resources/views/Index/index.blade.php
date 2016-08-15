@extends('default')



@section('pTitle','eXecom.ro')
@section('pDesc','eXecom.ro - Foruchat pentru Romania pentru toate domeniile de activitate')
@section('pURL',APP_PUBLIC_URL)






@section('container')


    <div class="container">
        <div class="row">

            <div class="col-xs-12">


                {{-- Containerul de categorii pe prima pagina --}}
                <div class="row">

                    <div class="col-md-3 col-sm-4 ">
                        <?php $n=0; ?>
                        @foreach($categories->where('back_to',0) as $c)

                                <div class="col-xs-12">
                                    <i class="fa fa-folder-o"></i> <a href="{{route('foruchat',$c->slug)}}" title="{{$c->categorie}}">{{ucwords($c->categorie)}}</a><br>
                                    @foreach($categories->where('back_to',$c->id) as $cs)
                                            <div style="margin-left: 15px;">
                                                <i class="fa fa-angle-right"></i> <a href="{{route('foruchat',$cs->slug)}}" title="{{$cs->categorie}}">{{$cs->categorie}}</a>
                                            </div>
                                    @endforeach
                                    <br><br>
                                </div>

                                @if($n==7||$n==15||$n==25)
                                </div><div class="col-md-3 col-sm-4 ">
                                @endif

                        <?php $n++; ?>




                        @endforeach
                    </div>

                </div>

        </div>
    </div>

@endsection










