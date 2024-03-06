<item>
    <title>{{ $post->title }} - Huuk - Travel platform powered by AI</title>
    <guid>{{ route('posts.show', $post) }}</guid>
    <pubDate>{{ $post->posted_at->format('r') }}</pubDate>
    <author>{{ $post->author->email }} ({{ $post->author->fullname }})</author>
    <description>{{ $post->excerpt() }}</description>
</item>
