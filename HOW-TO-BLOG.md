# How to add a new blog post

1. Create a new file in `src/blog/posts/`, named after your post, e.g. `src/blog/posts/my-post-title.md`.
2. Add front matter at the top:

   ```
   ---
   layout: post.njk
   title: My Post Title
   date: 2026-08-04
   tags: ["post"]
   summary: One sentence describing the post (used on the blog index page).
   description: Same sentence, or similar — used for the page's meta description.
   ---
   ```

3. Write the post below the front matter in Markdown.
4. Commit and push — or edit/commit the file directly on GitHub.com, no local setup needed.
5. A GitHub Action builds the site automatically and pushes the result. Hostinger picks it up the same way it deploys everything else on this site. Give it a minute or two, then check https://johnnyonion.com/blog/.

## Previewing locally before publishing (optional)

```
npm install   # once
npm run serve # starts a local preview server and rebuilds as you edit
```

## Editing the look of the blog

- Shared header/nav/footer/contact/subscribe chrome: `src/_includes/layout.njk`
- Individual post wrapper (date line, etc.): `src/_includes/post.njk`
- Blog index/listing page: `src/blog/index.njk`
- RSS feed: `src/blog/feed.njk`
