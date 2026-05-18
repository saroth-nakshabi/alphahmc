@extends('front/layout-2')

@section('page_title', 'Search Results | Alpha Health Group')

@section('content')

<div style="min-height: 70vh; padding: 160px 8%; background: #f8fafc;">

    {{-- Header --}}
    <div style="margin-bottom: 40px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: #1e293b; margin-bottom: 8px;">
            Search Results
        </h2>

        @if($query)
            <p style="color: #64748b; font-size: 1rem;">
                Found <strong>{{ $results->count() }}</strong> result(s) for
                <strong style="color: #009095;">"{{ $query }}"</strong>
            </p>
        @endif
    </div>

    {{-- Search bar on results page --}}
    <div style="margin-bottom: 40px;">
        <form action="{{ route('front.search') }}" method="GET"
              style="display:flex; gap:10px; max-width: 500px;">
            <input
                type="text"
                name="s"
                value="{{ $query }}"
                placeholder="Search again..."
                style="flex:1; padding: 12px 18px; border: 1px solid #e2e8f0;
                       border-radius: 8px; font-size: 1rem; outline:none;
                       font-family: 'Roboto', sans-serif;"
            />
            <button type="submit"
                style="padding: 12px 24px; background: linear-gradient(135deg, #009095, #0056a6);
                       color: #fff; border: none; border-radius: 8px;
                       font-size: 1rem; cursor: pointer;">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    {{-- No Results --}}
    @if($results->isEmpty())
        <div style="text-align: center; padding: 60px 0; color: #94a3b8;">
            <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 16px; display: block; color: #cbd5e1;"></i>
            <p style="font-size: 1.2rem; color: #64748b; margin-bottom: 8px;">
                No results found for <strong>"{{ $query }}"</strong>
            </p>
            <p style="color: #94a3b8;">Try different keywords or browse our services.</p>
            <a href="{{ route('front.all-services') }}"
               style="display: inline-block; margin-top: 24px; padding: 12px 28px;
                      background: linear-gradient(135deg, #009095, #0056a6);
                      color: #fff; border-radius: 8px; text-decoration: none;
                      font-family: 'Outfit', sans-serif;">
                Browse All Services
            </a>
        </div>

    {{-- Results Grid --}}
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @foreach($results as $result)

                @php
                    $color  = $result['type'] === 'Service' ? '#009095'
                            : ($result['type'] === 'Blog'    ? '#4CAF50' : '#0056a6');
                    $bgColor = $result['type'] === 'Service' ? '#e0f7f7'
                            : ($result['type'] === 'Blog'    ? '#e8f5e9' : '#e3f0ff');
                    $icon   = $result['type'] === 'Service' ? 'fa-stethoscope'
                            : ($result['type'] === 'Blog'    ? 'fa-newspaper' : 'fa-diagram-project');
                @endphp

                <a href="{{ $result['url'] }}" style="text-decoration: none; color: inherit;">
                    <div style="
                        background: #fff;
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 24px;
                        border-left: 4px solid {{ $color }};
                        transition: all 0.3s ease;
                        height: 100%;
                    "
                    onmouseover="this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'; this.style.transform='translateY(-3px)'"
                    onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">

                        {{-- Type Badge --}}
                        <span style="
                            font-size: 0.72rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                            padding: 4px 12px;
                            border-radius: 20px;
                            background: {{ $bgColor }};
                            color: {{ $color }};
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            margin-bottom: 14px;
                        ">
                            <i class="fas {{ $icon }}"></i>
                            {{ $result['type'] }}
                        </span>

                        {{-- Title --}}
                        <h4 style="
                            font-family: 'Outfit', sans-serif;
                            font-size: 1.05rem;
                            font-weight: 600;
                            color: #1e293b;
                            margin-bottom: 10px;
                            line-height: 1.4;
                        ">
                            {{ $result['title'] }}
                        </h4>

                        {{-- Excerpt --}}
                        @if($result['excerpt'])
                            <p style="
                                font-size: 0.88rem;
                                color: #64748b;
                                line-height: 1.6;
                                margin: 0 0 16px;
                            ">
                                {{ $result['excerpt'] }}
                            </p>
                        @endif

                        {{-- Read more --}}
                        <span style="
                            font-size: 0.85rem;
                            font-weight: 600;
                            color: {{ $color }};
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                        ">
                            View {{ $result['type'] }}
                            <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                        </span>

                    </div>
                </a>

            @endforeach
        </div>
    @endif

</div>

@endsection