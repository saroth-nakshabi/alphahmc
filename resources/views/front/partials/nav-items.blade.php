{{-- Third column of the mega menu: services and packages per category, guides per topic, case studies per
     project category. Served by /nav-items and inserted by nav.js when the menu first opens, so these
     ~300 links are not on every page (SEO brief "alphahmc-reviewers-and-linking" 3a, 30 Oct 2026). --}}
@php $rendered_category_ids = []; @endphp
@foreach ($main_categories as $main_category)
    @foreach ($main_category->mergedCategories as $category)
        @if (!in_array($category->id, $rendered_category_ids))
            @php $rendered_category_ids[] = $category->id; @endphp
            @foreach ($category->services as $service)
                <li class="service-item filter-sub_{{ $category->id }}" style="display: none;">
                    <a href="{{ route('front.service', $service->slug) }}">
                        {{ $service->name }}
                    </a>
                </li>
            @endforeach
            @foreach ($category->serviceGroups as $group)
                <li class="service-item filter-sub_{{ $category->id }}" style="display: none;">
                    <a href="{{ route('service-packages', $group->slug) }}">
                        {{ $group->name }}
                    </a>
                </li>
            @endforeach
        @endif
    @endforeach
@endforeach

{{-- Knowledge Base: blogs under each tag --}}
@if (isset($nav_blog_tags))
    @foreach ($nav_blog_tags as $tag)
        @foreach ($tag->blogs as $blog)
            <li class="service-item filter-tag_{{ $tag->id }}" style="display: none;">
                <a href="{{ route('front.singleBlog', $blog->slug) }}">{{ $blog->title }}</a>
            </li>
        @endforeach
    @endforeach
@endif

{{-- Case Studies: projects under each category --}}
@if (isset($nav_project_categories))
    @foreach ($nav_project_categories as $pcat)
        @foreach ($pcat->projects as $project)
            <li class="service-item filter-pcat_{{ $pcat->id }}" style="display: none;">
                <a href="{{ route('front.project_details', $project->slug) }}">{{ $project->name }}</a>
            </li>
        @endforeach
    @endforeach
@endif
