@php
    $googleReviews = config('google-reviews');
    $sectionId = $sectionId ?? null;
    $heading = $heading ?? 'Real stories from Google.';
    $headingId = $sectionId ? $sectionId.'-title' : 'google-reviews-title';
    $isCompact = $compact ?? false;
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
                    <small>{{ $googleReviews['review_count'] }} Google reviews ↗</small>
                </a>
                <a class="button button--dark" href="{{ $googleReviews['write_review_url'] }}" target="_blank" rel="noopener noreferrer">Leave a review ↗</a>
            </div>
        </div>

        <div class="google-reviews__carousel reveal" data-google-reviews-carousel>
            <button class="google-reviews__arrow google-reviews__arrow--previous" type="button" data-google-reviews-prev aria-label="Previous Google reviews">←</button>

            <div class="google-reviews__viewport">
                <div class="google-reviews__track" data-google-reviews-track>
                    @foreach ($googleReviews['reviews'] as $review)
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

            <button class="google-reviews__arrow google-reviews__arrow--next" type="button" data-google-reviews-next aria-label="Next Google reviews">→</button>

            <span class="google-reviews__position" aria-live="polite"><b data-google-reviews-current>1–3</b> / {{ count($googleReviews['reviews']) }}</span>
        </div>
    </div>
</section>
