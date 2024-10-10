import PostsAjax from './../common/postsAjax';


export default {
  init() {
    const containerClass = '.category .wp-block-everside-cards-category';

    const category = new PostsAjax({
      container: containerClass,
      containerPosts: '.wp-block-everside-cards-category-container',
      endpoint: 'wp/v2/posts',
      params: {
        categories: document.querySelector(containerClass).dataset.categoryId,
        hide_post: 0,
        per_page: 9,
      },
      renderTemplate: (post) => {
        const placeholderImage = everside_js.plugin_url + 'src/images/placeholder.png';
        let featuredImage;

        if( post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia']['0'].media_details.sizes.card_large ) {
          featuredImage = post._embedded['wp:featuredmedia']['0'].media_details.sizes.card_large.source_url;
        }

        return `
          <article class="content card -inherit-colors background--very-light-blue">
            <figure>
              <a href="${post.link}">
                <img class="content-image" src="${featuredImage ? featuredImage : placeholderImage }" alt="" />
              </a>
            </figure>

            <header class="content-details">
              <h3><a href="${post.link}">${post.title.rendered}</a></h3>
              <p>${post.excerpt.rendered}</p>
            </header>
          </article>
        `;
      },
    });

    category.loadPosts();

  },
};
