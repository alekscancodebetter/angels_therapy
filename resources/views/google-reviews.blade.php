@php
    $googleReviews = config('google-reviews');
    $sectionId = $sectionId ?? null;
    $heading = $heading ?? 'Real stories from Google.';
    $headingId = $sectionId ? $sectionId.'-title' : 'google-reviews-title';
    $carouselId = ($sectionId ?: 'google-reviews').'-carousel-track';
    $isCompact = $compact ?? false;
    $reviewGroup = $reviewGroup ?? 'home';
    $reviewKeys = $googleReviews['review_groups'][$reviewGroup] ?? $googleReviews['review_groups']['home'];
    $reviews = [];

    foreach ($reviewKeys as $reviewKey) {
        if (isset($googleReviews['reviews'][$reviewKey])) {
            $reviews[] = $googleReviews['reviews'][$reviewKey];
        }
    }
@endphp

<section @class(['google-reviews', 'section', 'google-reviews--compact' => $isCompact]) @if ($sectionId) id="{{ $sectionId }}" @endif aria-labelledby="{{ $headingId }}">
    <div class="container">
        <div class="google-reviews__heading reveal">
            <div>
                <p class="eyebrow eyebrow--gold">Verified on Google</p>
                <h2 id="{{ $headingId }}">{{ $heading }}</h2>
            </div>
            <div class="google-reviews__summary">
                <a class="google-reviews__score" href="{{ $googleReviews['reviews_url'] }}" target="_blank" rel="noopener noreferrer" aria-label="View all {{ $googleReviews['review_count'] }} Google reviews in a new tab">
                    <strong>{{ $googleReviews['rating'] }}</strong>
                    <span class="google-reviews__stars" aria-label="{{ $googleReviews['rating'] }} out of 5 stars">★★★★★</span>
                    <small>View all {{ $googleReviews['review_count'] }} Google reviews ↗</small>
                </a>
                <a class="button button--dark" href="{{ $googleReviews['write_review_url'] }}" target="_blank" rel="noopener noreferrer">Leave a review <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
            </div>
        </div>

        <div class="google-reviews__carousel reveal" data-google-reviews-carousel>
            <button class="google-reviews__arrow google-reviews__arrow--previous" type="button" data-google-reviews-prev aria-label="Previous Google reviews" aria-controls="{{ $carouselId }}">←</button>

            <div class="google-reviews__viewport">
                <div class="google-reviews__track" id="{{ $carouselId }}" data-google-reviews-track>
                    @foreach ($reviews as $review)
                        <a class="google-review-card" href="{{ $review['url'] }}" target="_blank" rel="noopener noreferrer" data-google-review-card aria-label="Read {{ $review['author'] }}'s full Google review in a new tab">
                            <span class="google-review-card__source"><b aria-hidden="true">G</b> Google review <i aria-hidden="true">↗</i></span>
                            <span class="google-review-card__stars" aria-label="{{ $review['rating'] }} out of 5 stars">{{ str_repeat('★', $review['rating']) }}</span>
                            <blockquote>
                                <p>“{{ $review['excerpt'] }}”</p>
                                <footer>{{ $review['author'] }}</footer>
                            </blockquote>
                            <span class="google-review-card__link">Read the full review <b aria-hidden="true">↗</b></span>
                        </a>
                    @endforeach
                </div>
            </div>

            <button class="google-reviews__arrow google-reviews__arrow--next" type="button" data-google-reviews-next aria-label="Next Google reviews" aria-controls="{{ $carouselId }}">→</button>

            <span class="google-reviews__position" aria-live="polite" aria-atomic="true">Reviews <b data-google-reviews-current>1–{{ min(3, count($reviews)) }}</b> of {{ count($reviews) }}</span>
        </div>
    </div>
</section>
