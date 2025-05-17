<div class="col-lg-4">
    <!-- Search Form Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <form action="{{route('blog.index')}}/" method="get">
            <div class="input-group">
                <input type="text" name='search' value="{{request('search')}}" class="form-control p-3" placeholder="Search Here">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
    <!-- Search Form End -->

    <!-- Category Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0">Categories</h3>
        </div>
        <div class="link-animated d-flex flex-column justify-content-start">

            @forelse ($categories as $category)
                <a class="h5 fw-semi-bold bg-light rounded py-2 px-3 mb-2 {{ request('cid') == $category->id ? 'activated' : '' }}" href="{{route('blog.index',['category' => $category->name,'cid' => $category->id])}}"><i
                        class="bi bi-arrow-right me-2"></i>{{ $category->name }}</a>
            @empty
                <a class="h5 fw-semi-bold bg-light rounded py-2 px-3 mb-2" href="#"><i
                        class="bi bi-arrow-right me-2"></i>No Category Added</a>
            @endforelse
        </div>
    </div>
    <!-- Category End -->

    <!-- Most Commented Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0">Most Commented</h3>
        </div>

        @if (isset($mostCommented))

            @forelse ($mostCommented as $post)
                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid"
                        src="{{ isset($post->photo) ? asset($post->photo) : asset('frontend/assets/img/blog-1.jpg') }}"
                        style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="{{ route('blog.detail', ['slug' => $post->slug]) }}"
                        class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0 w-100">
                        {{ Str::words(strip_tags($post->title), 3, '...') }}
                    </a>
                </div>
            @empty
                <h3>No Recent Post Added</h3>
            @endforelse


        @endif
    </div>
    <!-- Recent Post End -->

    <!-- Recent Post Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0">Recent Post</h3>
        </div>

        @if (isset($recentPosts))

            @forelse ($recentPosts as $post)
                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid"
                        src="{{ isset($post->photo) ? asset($post->photo) : asset('frontend/assets/img/blog-1.jpg') }}"
                        style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="{{ route('blog.detail', ['slug' => $post->slug]) }}"
                        class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0 w-100">
                        {{ Str::words(strip_tags($post->title), 3, '...') }}
                    </a>
                </div>
            @empty
                <h3>No Recent Post Added</h3>
            @endforelse


        @endif
    </div>
    <!-- Recent Post End -->

    <!-- Tags Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0">Tag Cloud</h3>
        </div>
        <div class="d-flex flex-wrap m-n1">

            @forelse ($tags as $tag)
                <a href="{{route('blog.index',['tag' => $tag->name,'tid' => $tag->id])}}" class="btn btn-light m-1 {{ request('tag') == $tag->name ? 'activated' : '' }}">{{ $tag->name }}</a>
            @empty
                <a href="" class="btn btn-light m-1">No Tag addes</a>
            @endforelse
        </div>
    </div>
    <!-- Tags End -->


    <!-- Active User Start -->
    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s"
        style="visibility: visible; animation-delay: 0.1s; animation-name: slideInUp;">
        <div class="section-title section-title-sm position-relative pb-3 mb-4">
            <h3 class="mb-0">Active user</h3>
        </div>

        @if (isset($activeUsers))

            @forelse ($activeUsers as $user)
                <div class="d-flex mb-3">
                    <div class="d-block">
                        {{ Str::words(strip_tags($user->name), 3, '...') }}
                    </div>
                    <div class="" style="margin-left : 15px">
                        {{ $user->blog_count }} posts
                    </div>
                </div>
            @empty
                <h3>No User Added</h3>
            @endforelse


        @endif
    </div>
    <!-- Recent Post End -->

</div>
