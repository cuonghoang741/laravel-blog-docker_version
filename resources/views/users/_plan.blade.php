<x-card>
  <h4 class="card-title">
    <a href="{{ route('posts.show', $post) }}">
      {{ $post->title }}
    </a>
  </h4>

  <p class="card-text">
    <small class="text-body-secondary">@humanize_date($post->posted_at)</small><br>
    <small class="text-body-secondary">
      <x-icon name="comments" prefix="fa-regular" /> {{ $post->comments_count }}
      <x-icon name="heart" prefix="fa-regular" /> {{ $post->likes_count }}
    </small>
  </p>
</x-card>
