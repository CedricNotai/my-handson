@extends('layouts.app')
@section('content')

<div id="results-page">
    {{-- Current location --}}
    <div class="current-location container">
        <h1>Résultats de votre recherche</h1>
        @if (!empty($currentLocation))
            <p>Votre emplacement : {{ $currentLocation }}</p>
        @else 
            <p>Impossible de récupérer votre emplacement.</p>
        @endif
    </div>

    {{-- Map --}}
    <div class="map-container">
        <div id="my-map"></div>
    </div>

    {{-- Results --}}
    <div class="container">
        {{-- Results lists view --}}
        @if (is_null($itinerary))
            {{-- Warning --}}
            <div class="warning">
                @if (is_null($storeResults) && is_null($recyclingCenters))
                    <p>La recherche n'a pas abouti, merci de réessayer.</p>
                @else
                    <p>Avant de jeter votre équipement, avez-vous pensé à le réparer ?</p>
                @endif
            </div>

            {{-- Lists --}}
            <div class="results-lists">
                {{-- Stores list --}}
                <div class="stores-list list">
                    @if (is_null($storeResults))
                        <div class="list-head">
                            <p>Aucun magasin n'a été trouvé.</p>
                        </div>
                    @else
                        <div class="list-head">
                            <h2>Les magasins d'électronique autour de vous</h2>
                            <p>Ces magasins pourraient vous conseiller pour la réparation de votre matériel.</p>    
                        </div>

                        @foreach ($storeResults as $result)
                            {{-- Stores list item --}}
                            <div id ="{{$result['anchor']}}" class="list-item">
                                <div class="item-line">
                                    <div class="icon">@svg('store')</div>
                                    <p>{{ $result['name'] }}</p>
                                </div>
                                <div class="item-line">
                                    <div class="icon">@svg('road')</div>
                                    <p>{{ $result['address_line2'] }}</p>
                                </div>

                                @if ($result['contact_phone']) 
                                    <div class="item-line">
                                        <div class="icon">@svg('phone')</div>
                                        <p>{{ $result['contact_phone'] }}</p>
                                    </div>
                                @endif

                                @if ($result['website'] || $result['contact_website']) 
                                    <div class="item-line">
                                        <div class="icon">@svg('web')</div>
                                        @if ($result['website']) 
                                            <p><a href="{{$result['website']}}">Site de {{ $result['name'] }}</a></p>
                                        @elseif ($result['contact_website'])
                                            <p><a href="{{$result['contact_website']}}">Site de {{ $result['name'] }}</a></p>
                                        @endif
                                    </div>
                                @endif

                                @if ($result['opening_hours']) 
                                    <div class="item-line multiline">
                                        <div class="icon">@svg('clock')</div>
                                        <div>
                                            <p><span class="item-title">Horaires d'ouverture :</span></p>
                                            {!! ' ' . $result['opening_hours'] !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Recycling centers list --}}
                <div class="recycling-centers-list list">
                    @if (is_null($recyclingCenters))
                        <div class="list-head">
                            <p>La recherche de centres de tri n'a pas réussi, veuillez réessayer.</p>
                        </div>
                    @else
                        <div class="list-head">
                            <h2>Les centres de tri autour de vous</h2>
                            <p>Ces centres acceptent les équipements électriques et électroniques hors d'usage.</p>
                        </div>

                        @foreach ($recyclingCenters as $center)
                            {{-- Recycling centers list item --}}
                            <div id ="{{$center['anchor']}}" class="list-item">
                                <div class="item-line">
                                    <div class="icon">@svg('store')</div>
                                    <p><span class="item-title">Nom : </span>{{ $center['name'] }}</p>
                                </div>
                                <div class="item-line">
                                    <div class="icon">@svg('road')</div>
                                    <p><span class="item-title">Adresse : </span>{{ $center['address'] . ', ' . $center['postal_code'] . ' - ' . $center['city'] }}</p>
                                </div>
                                <div class="item-line">
                                    <div class="icon">@svg('phone')</div>
                                    <p><span class="item-title">Téléphone : </span>{{ $center['phone'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @else
            {{-- Results itinerary view --}}
            @php 
                $queryParams = "name= " . $itinerary['destination_name'] 
                    . "&latitude=" . $itinerary['location_latitude'] 
                    . "&longitude=" . $itinerary['location_longitude']
                    . "&destLatitude=" . $itinerary['destination_latitude']
                    . "&destLongitude=" . $itinerary['destination_longitude'];
                $modes = [
                    'walk' => 'À pied', 
                    'bicycle' => 'À vélo', 
                    'drive' => 'En voiture',
                ];
            @endphp
            <div id="results-itinerary">
                {{-- Itinerary mode buttons --}}
                <div class="modes-buttons">
                    @foreach ($modes as $key => $mode)
                        @php 
                            if ($itinerary['mode'] === $mode ) {
                                $currentModeIcon = $key;
                            }
                        @endphp
                        @if($itinerary['mode'] !== $mode) 
                            <a href="/results/itinerary?mode={{$key}}&{{$queryParams}}">
                                @svg($key)
                                {{$mode}}
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Itinerary details --}}
                <div class="list">
                    <div class="list-head">
                        <h2>Détails de votre itinéraire vers {{ $itinerary['destination_name'] }}</h2>
                    </div>
                    <div class="list-item">
                        <div class="item-line">
                            <div class="icon">@svg('store')</div>
                            <p>{{ $itinerary['destination_address'] }}</p>
                        </div>
                        <div class="item-line">
                            <div class="icon">@svg($currentModeIcon)</div>
                            <p>{{ $itinerary['mode'] }}</p>
                        </div>
                        <div class="item-line">
                            <div class="icon"> @svg('clock')</div>
                            <p>{{ $itinerary['time'] }}</p>
                        </div>
                        <div class="item-line">
                            <div class="icon">@svg('distance')</div>
                            <p>{{ $itinerary['distance'] }} mètres</p>
                        </div>

                        <a class="go-back"href="/results?latitude={{$itinerary['location_latitude']}}&longitude={{$itinerary['location_longitude']}}">Retourner à la liste des résultats</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer-scripts')
    @include('scripts.map-script')
@endsection