@extends('layouts.user')

@section('content')
<style>
    /* Dynamic background gradients based on condition */
    .weather-card.clear { background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%); }
    .weather-card.cloudy { background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%); }
    .weather-card.rainy { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }
    .weather-card.foggy { background: linear-gradient(135deg, #cbd5e1 0%, #e2e8f0 100%); color: #475569 !important; }

    .text-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    /* Skeleton Loading Animation */
    .skeleton {
        background: #e2e8f0;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        border-radius: 10px;
        background-size: 200% 100%;
        animation: shine 1.5s linear infinite;
    }
    .skeleton-icon { width: 80px; height: 80px; border-radius: 50%; }
    .skeleton-text { height: 20px; border-radius: 4px; }
    .skeleton-block { height: 120px; border-radius: 20px; }

    @keyframes shine {
        to { background-position-x: -200%; }
    }
</style>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- Search Bar --}}
            <form action="{{ route('fun.index') }}" method="GET" class="mb-4" id="weatherForm">
                <div class="input-group shadow-sm border-0 rounded-pill overflow-hidden">
                    <input type="text" name="city" value="{{ request('city') }}" class="form-control border-0 px-4 py-3" placeholder="Enter city name (e.g. Tokyo, London)..." style="outline: none; box-shadow: none;">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- 1. SKELETON LOADER (Visible on Page Load) --}}
            <div id="weather-skeleton">
                <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 32px;">
                    <div class="skeleton skeleton-icon mb-4 mx-auto"></div>
                    <div class="skeleton skeleton-text w-50 mx-auto mb-2"></div>
                    <div class="skeleton skeleton-text w-25 mx-auto mb-4"></div>
                    <div class="skeleton skeleton-block mb-4"></div>
                    <div class="row g-3">
                        <div class="col-6"><div class="skeleton skeleton-text w-100" style="height: 60px; border-radius: 15px;"></div></div>
                        <div class="col-6"><div class="skeleton skeleton-text w-100" style="height: 60px; border-radius: 15px;"></div></div>
                    </div>
                </div>
            </div>

            {{-- 2. ACTUAL WEATHER CARD (Hidden initially) --}}
            <div id="weather-content" class="d-none">
                <div class="card border-0 shadow-lg p-4 p-md-5 text-center text-white weather-card {{ $condition }}" style="border-radius: 32px;">
                    
                    {{-- Refresh Indicator --}}
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 px-3 py-1 rounded-pill border border-white border-opacity-10">
                            <div class="spinner-grow spinner-grow-sm me-2 text-white" role="status" style="width: 10px; height: 10px;"></div>
                            <span class="small fw-bold text-shadow">Refreshing in: <span id="timer">60</span>s</span>
                        </div>
                    </div>

                    {{-- Top Section: Icon --}}
                    <div class="mb-3">
                        @if($condition == 'clear') 
                            <i class="bi bi-sun-fill" style="font-size: 5rem;"></i>
                        @elseif($condition == 'cloudy') 
                            <i class="bi bi-clouds-fill" style="font-size: 5rem;"></i>
                        @elseif($condition == 'rainy') 
                            <i class="bi bi-cloud-rain-heavy-fill" style="font-size: 5rem;"></i>
                        @else 
                            <i class="bi bi-cloud-haze2-fill" style="font-size: 5rem;"></i>
                        @endif
                    </div>
                    
                    <h1 class="fw-bold mb-1 text-shadow">{{ $locationName }}</h1>
                    <p class="opacity-75 mb-4 ls-wide fw-medium">CONDITION: {{ strtoupper($condition) }}</p>

                    {{-- MIDDLE SECTION --}}
                    <div class="bg-white bg-opacity-20 py-4 rounded-4 mb-4 shadow-sm border border-white border-opacity-10">
                        <span class="text-uppercase small fw-bold opacity-75 d-block mb-1">Current Temperature</span>
                        <h1 class="display-1 fw-bold mb-0 text-shadow" style="letter-spacing: -4px;">
                            {{ round($weather['current_weather']['temperature']) }}°
                        </h1>
                    </div>

                    {{-- Bottom Section: Details --}}
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-white border-opacity-10">
                                <i class="bi bi-wind fs-4 mb-1 d-block"></i>
                                <div class="small opacity-75">Wind Speed</div>
                                <div class="fw-bold text-shadow">{{ $weather['current_weather']['windspeed'] }} <span class="small opacity-50">km/h</span></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-white border-opacity-10">
                                <i class="bi bi-clock-history fs-4 mb-1 d-block"></i>
                                <div class="small opacity-75">Local Time</div>
                                <div class="fw-bold text-shadow">{{ date('h:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex justify-content-between align-items-center opacity-50 px-2">
                        <span style="font-size: 0.7rem;"><i class="bi bi-cpu me-1"></i> API: Open-Meteo REST</span>
                        <span style="font-size: 0.7rem;"><i class="bi bi-check2-circle me-1"></i> HTTP 200 OK</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // 1. Logic for Skeleton Loading Toggle
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.getElementById('weather-skeleton').classList.add('d-none');
            document.getElementById('weather-content').classList.remove('d-none');
        }, 1200); // Shows skeleton for 1.2 seconds
    });

    // 2. Logic for Auto-Refresh Timer
    let timeLeft = 60;
    const timerElement = document.getElementById('timer');
    const weatherForm = document.getElementById('weatherForm');

    const countdown = setInterval(() => {
        timeLeft--;
        if(timerElement) timerElement.innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            weatherForm.submit(); // Auto-refresh by submitting form
        }
    }, 1000);
</script>
@endsection