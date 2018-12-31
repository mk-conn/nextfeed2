export default function(){
  // Add your transitions here, like:
    this.transition(
      this.fromRoute('index'),
      this.toRoute('index.feed.articles'),
      this.use('toLeft'),
      this.reverse('toRight')
    );
}
