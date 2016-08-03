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
                        @foreach($categories as $c)

                            @if($c->back_to == 0 && !empty($c->categorie) )

                                <div class="col-xs-12">
                                    <i class="fa fa-folder-o"></i> <a href="{{\App\SEO::linkDirector($c)->link}}" title="{{$c->categorie}}">{{ucwords($c->categorie)}}</a><br>
                                    @foreach($categories as $cs)
                                        @if($cs->back_to == $c->id)
                                            <div style="margin-left: 15px;">
                                                <i class="fa fa-angle-right"></i> <a href="{{\App\SEO::linkDirector($cs)->link}}" title="{{$cs->categorie}}">{{$cs->categorie}}</a>
                                            </div>
                                        @endif
                                    @endforeach
                                    <br><br>
                                </div>

                                @if($n==7||$n==15||$n==25)
                    </div><div class="col-md-3 col-sm-4 ">
                        @endif

                        <?php $n++; ?>
                        @endif



                        @endforeach
                    </div>

                </div>

        </div>
    </div>

@endsection










