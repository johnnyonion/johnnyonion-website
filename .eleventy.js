const pluginRss = require("@11ty/eleventy-plugin-rss");

module.exports = function (eleventyConfig) {
	eleventyConfig.addPlugin(pluginRss);

	eleventyConfig.addFilter("readableDate", (dateObj) => {
		return new Date(dateObj).toLocaleDateString("en-US", {
			year: "numeric",
			month: "long",
			day: "numeric",
		});
	});

	eleventyConfig.addFilter("htmlDateString", (dateObj) => {
		return new Date(dateObj).toISOString().split("T")[0];
	});

	return {
		dir: {
			input: "src/blog",
			includes: "../_includes",
			output: "blog",
		},
		markdownTemplateEngine: "njk",
		htmlTemplateEngine: "njk",
	};
};
